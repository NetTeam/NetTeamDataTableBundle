<?php

namespace NetTeam\Bundle\DataTableBundle\Export;

use PHPExcel_Worksheet;
use PHPExcel_Style_Fill;
use Liuggio\ExcelBundle\Service\ExcelContainer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use NetTeam\Bundle\DataTableBundle\Column\ColumnInterface;

/**
 * Serwis do eksportowania danych z DataTable do plików Excel
 *
 * @author Paweł Macyszyn <pawel.macyszyn@netteam.pl>
 */
class XlsExport implements ExportInterface
{
    /**
     * @var ExcelContainer
     */
    protected $excel;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @param ExcelContainer $excel
     * @param Translator     $translator
     */
    public function __construct(ExcelContainer $excel, Translator $translator)
    {
        $this->excel = $excel;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function export($filename, array $columns, array $data, array $options = array())
    {
        $sheet = $this->getSheet($filename);

        $this->setColumns($columns, $sheet);
        $this->setRows($data, $sheet);

        return $this->getResponse($filename);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'xls';
    }

    /**
     * Ustawia i zwraca arkusz dokumentu xls
     *
     * @param  string             $filename
     * @return PHPExcel_Worksheet
     */
    protected function getSheet($filename)
    {
        $sheet = $this->excel->excelObj->setActiveSheetIndex(0);

        $sheet->getPageSetup()->setOrientation('landscape');

        // drukowanie na pojedyńczej stronie
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $sheet->setTitle($filename);
        $sheet->getDefaultStyle()->getFont()->setSize(8);
        $sheet->getDefaultStyle()->getAlignment()->setWrapText(true);
        $sheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $sheet->getPageMargins()
            ->setTop(0.5)
            ->setBottom(0.5)
            ->setLeft(0.5)
            ->setRight(0.5);

        return $sheet;
    }

    /**
     * @param array              $columns
     * @param PHPExcel_Worksheet $sheet
     */
    protected function setColumns($columns, $sheet)
    {
        $columnIndex = 0;

        foreach ($columns as $column) {
            $this->addColumn($column, $sheet, $columnIndex);
            $columnIndex++;
        }
    }

    /**
     * Dodaje do arkusza nagłówek kolumny
     *
     * @param ColumnInterface    $column
     * @param PHPExcel_Worksheet $sheet
     * @param integer            $key
     */
    protected function addColumn(ColumnInterface $column, $sheet, $key)
    {
        $sheet->setCellValueByColumnAndRow($key, 1, $this->translator->trans($column->getCaption()));
        $sheet->getStyleByColumnAndRow($key, 1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyleByColumnAndRow($key, 1)->getFill()->getStartColor()->setARGB('FFFFFF00');
        $sheet->getStyleByColumnAndRow($key, 1)->getAlignment()->setWrapText(true);
        $sheet->getColumnDimensionByColumn($key)->setAutoSize(true);
    }

    /**
     * Tworzenie danych dla arkusza w pliku Excel
     *
     * @param array              $data
     * @param PHPExcel_Worksheet $sheet
     */
    protected function setRows($data, $sheet)
    {
        foreach ($data as $rowIndex => $row) {
            $sheet->getRowDimension($rowIndex)->setRowHeight(-1);
            foreach ($row['columns'] as $columnIndex => $columnValue) {
                $sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex+2, $this->translator->trans($columnValue->getValue()));
            }
        }
    }

    /**
     * @param  string   $filename
     * @return Response
     */
    protected function getResponse($filename)
    {
        $response = $this->excel->getResponse();
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename='.str_replace(' ', '_', $filename).'.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }
}
