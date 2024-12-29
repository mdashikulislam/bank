<?php
namespace App\Controllers;
use App\Models\AttributeModel;
use App\Models\ExcelModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
class Excels extends AdminBaseController{
    public $title = 'Excel';
    public $menu = 'excel';

    public function index()
    {
        $this->permissionCheck('excels_list');
        $excels = (new ExcelModel())->getAllExcels();
        return view('admin/excels/list',compact('excels'));
    }

    public function add()
    {
        $this->permissionCheck('excels_add');
        return view('admin/excels/add');
    }

    public function save()
    {
        $this->permissionCheck('excels_add');
        postAllowed();
        $validation = service('validation');
        $request    = service('request');
        $data = $request->getPost();
        $validation->setRules([
            'bank_id' => 'required',
            'file' => 'uploaded[file]|ext_in[file,xlsx,csv]',
        ]);
        if (!$validation->run($data)) {
            return redirect()->back()->withInput()->with('notifyError', implode(', ', $validation->getErrors()));
        }
        $existHeader = (new AttributeModel())->where('bank_id', $data['bank_id'])->first();
        if(!$existHeader){
            return redirect()->back()->with('notifyError', 'Attribute not found');
        }
        $defineHeader = json_decode($existHeader->name,true);
        $reader = new Xlsx();
        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $header = @$sheetData[0];
        if (empty($header)){
            return redirect()->back()->with('notifyError', 'File is empty');
        }
        $missingValues = array_diff($defineHeader, $header);
        if (!empty($missingValues)){
            return redirect()->back()->with('notifyError', 'Missing attribute: ' . implode(', ', $missingValues));
        }
        if (count($sheetData) < 2){
            return redirect()->back()->with('notifyError', 'No data found in file');
        }
        $excelBody = [];
        foreach ($sheetData as $key => $value) {
            if ($key == 0) continue;
            $filteredRow = [];
            foreach ($defineHeader as $headerColumn) {
                $columnIndex = array_search($headerColumn, $header);
                if ($columnIndex !== false && isset($value[$columnIndex])) {
                    $filteredRow[] = $value[$columnIndex];
                }
            }
            $excelBody[] = $filteredRow;
        }
        $createData = [
            'bank_id' => $data['bank_id'],
            'header' => json_encode($defineHeader),
            'data'=> json_encode($excelBody)
        ];
        try {
            $excel = new \App\Models\ExcelModel();
            $excel = $excel->create($createData);
            model('App\Models\ActivityLogModel')->add("New Excel #$excel Created by User: #" . logged('id'));
            return redirect()->to('excels')->with('notifySuccess', 'New Excel Created Successfully');
        }catch (\Exception $e) {
            return redirect()->back()->with('notifyError', 'Failed to create excel: ' . $e->getMessage());
        }
    }
    public function delete($id = 0)
    {
        $this->permissionCheck('excels_delete');
        $bank = (new ExcelModel())->find($id);
        if (empty($bank)){
            return redirect()->to('excels')->with('notifyError', 'Excel not found');
        }
        (new ExcelModel())->delete($id);
        model('App\Models\ActivityLogModel')->add("Excels #$id Deleted by User:".logged('name'));
        return redirect()->to('excels')->with('notifySuccess', 'Excel has been Deleted Successfully');
    }
}