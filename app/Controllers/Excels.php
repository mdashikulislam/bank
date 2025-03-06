<?php

namespace App\Controllers;

use App\Models\AttributeModel;
use App\Models\BankModel;
use App\Models\ExcelModel;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class Excels extends AdminBaseController
{
    public $title = 'Excel';
    public $menu = 'excel';

    public function index()
    {
        $this->permissionCheck('excels_list');
        $banks = (new BankModel())->getBankWithExcel();
        $user = logged();
        return view('admin/excels/list', compact('banks', 'user'));
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
        $request = service('request');
        $data = $request->getPost();

        // Validate uploaded file
        $validation->setRules([
            'bank_id' => 'required',
            'file' => 'uploaded[file]|ext_in[file,xlsx,csv]',
        ]);

        if (!$validation->run($data)) {
            return redirect()->back()->withInput()->with('notifyError', implode(', ', $validation->getErrors()));
        }

        // Fetch attributes dynamically for the given bank_id
        $existHeader = (new AttributeModel())->where('bank_id', $data['bank_id'])->first();
        if (!$existHeader) {
            return redirect()->back()->with('notifyError', 'Attribute not found');
        }

        // Decode attribute names from JSON
        $defineHeader = json_decode($existHeader->name, true);
        // Load the uploaded file
        $reader = new Xlsx();
        if ($_FILES['file']['type'] == 'text/csv') {
            $reader = new Csv();
        }

        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $fileHeader = @$sheetData[0];

        if (empty($fileHeader)) {
            return redirect()->back()->with('notifyError', 'File is empty');
        }

        // Ensure required attributes exist in the file
        $missingValues = array_diff($defineHeader, $fileHeader);
        if (!empty($missingValues)) {
            return redirect()->back()->with('notifyError', 'Missing attribute: ' . implode(', ', $missingValues));
        }

        if (count($sheetData) < 2) {
            return redirect()->back()->with('notifyError', 'No data found in file');
        }
        $excelBody = [];
        foreach ($sheetData as $key => $value) {
            if ($key == 0) continue; // Skip header row

            $filteredRow = [];
            foreach ($defineHeader as $headerColumn) {
                $columnIndex = array_search($headerColumn, $fileHeader);
                if ($columnIndex !== false) {
                    $filteredRow[$headerColumn] = isset($value[$columnIndex]) ? $value[$columnIndex] : null;
                } else {
                    $filteredRow[$headerColumn] = null;
                }
            }
            $excelBody[] = $filteredRow;
        }
        $currentUserId = logged('id');
        $currentUserRole = logged('role');

        if (!$currentUserId) {
            return redirect()->back()->with('notifyError', 'User is not logged in');
        }
        $loanNumberField = in_array('Loan Number', $defineHeader) ? 'Loan Number' : null;

        $db = \Config\Database::connect();
        $excelModel = new \App\Models\ExcelModel();
        $updatedRecords = 0;
        $insertedRecords = 0;
        $excelBody = array_reverse($excelBody);
        foreach ($excelBody as $body) {
            $orderedData = [];
            foreach ($defineHeader as $headerColumn) {
                $orderedData[$headerColumn] = $body[$headerColumn] ?? null;
            }
            $loanNumber = @$loanNumberField ? $body[$loanNumberField] : null;
            if ($loanNumber !== null) {
                $query = $db->query("SELECT * FROM excels WHERE bank_id = ? AND JSON_UNQUOTE(JSON_EXTRACT(data, '$.\"Loan Number\"')) = ?", [$data['bank_id'], $loanNumber]);
                $existingRecord = $query->getRow();
                if ($existingRecord) {
                    if ($existingRecord->user_id == $currentUserId) {
                        continue;
                    }
                    $sheetUserId = (new UserModel())->where('id', $existingRecord->user_id)->first();
                    if ($sheetUserId->role == 1 && $currentUserRole != 1) {
                        $excelModel = model(\App\Models\ExcelModel::class);
                        $excelModel->update($existingRecord->id,['user_id' => $currentUserId]);
                        $updatedRecords++;
                    }
                    continue;
                }
            }
            $formattedData = array_replace(array_flip($defineHeader), $orderedData);
            $excelModel->insert([
                'bank_id' => $data['bank_id'],
                'data' => json_encode($formattedData, JSON_FORCE_OBJECT),
                'user_id' => $currentUserId
            ]);
            $insertedRecords++;
        }

        // Display a meaningful success message
        if ($updatedRecords > 0 && $insertedRecords > 0) {
            return redirect()->to('excels')->with('notifySuccess', "$updatedRecords records updated, $insertedRecords new records inserted.");
        } elseif ($updatedRecords > 0) {
            return redirect()->to('excels')->with('notifySuccess', "$updatedRecords records updated.");
        } elseif ($insertedRecords > 0) {
            return redirect()->to('excels')->with('notifySuccess', "$insertedRecords new records inserted.");
        } else {
            return redirect()->to('excels')->with('notifyError', 'No data updated or inserted.');
        }
    }


    public function delete($id = 0)
    {
        $this->permissionCheck('excels_delete');
        $bank = (new ExcelModel())->find($id);
        if (empty($bank)) {
            return redirect()->to('excels')->with('notifyError', 'Excel not found');
        }
        (new ExcelModel())->delete($id);
        model('App\Models\ActivityLogModel')->add("Excels #$id Deleted by User:" . logged('name'));
        return redirect()->to('excels')->with('notifySuccess', 'Excel has been Deleted Successfully');
    }
}