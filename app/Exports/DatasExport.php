<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
//use Maatwebsite\Excel\Concerns\FromView;
//use Maatwebsite\Excel\Concerns\Exportable;

class DatasExport implements FromView
{
    use Exportable;

    private $data = null;
    private $queryName = null;
    private $id = null;
    private $prendpasencomptefiltre = null;


    public function __construct($data, $queryName, $id = null, $prendpasencomptefiltre = null)
    {
        $this->data = $data;
        $this->queryName = $queryName;
        $this->id = $id;
        $this->prendpasencomptefiltre = $prendpasencomptefiltre;
    }

    public function view(): View
    {
        $FileName = (isset($this->id) ? "detail-" : "") . "{$this->queryName}";
        if(isset($this->prendpasencomptefiltre))
        {
            $FileName = "feuille-"."{$this->queryName}";
        }

        return view("pdf.{$FileName}", [
            'data'       => $this->data["data"],
            'is_excel'  => true,
        ]);
    }
}