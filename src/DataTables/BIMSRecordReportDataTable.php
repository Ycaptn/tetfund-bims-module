<?php

namespace TETFund\BIMSOnboarding\DataTables;

use TETFund\BIMSOnboarding\Models\BIMSRecord;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Hasob\FoundationCore\Models\Organization;

class BIMSRecordReportDataTable extends DataTable
{
    protected $organization;

    public function __construct(Organization $organization){
        $this->organization = $organization;
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('Institution', function($bim_record){
                return $bim_record->beneficiary->short_name?? 'NILL';
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \TETFund\BIMSOnboarding\Models\BIMSRecord $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BIMSRecord $model)
    {
        return $model::select('id','beneficiary_id',
        \DB::raw('SUM(CASE WHEN `user_type` = \'non-academic\' THEN 1 ELSE 0 END) AS "Staffs"'),
        \DB::raw('SUM(CASE WHEN `user_type` = \'student\' THEN 1 ELSE 0 END) AS "Students"'),
        \DB::raw('SUM(CASE WHEN `is_verified` = \'0\' THEN 1 ELSE 0 END) AS "Unverified"'),
        \DB::raw('SUM(CASE WHEN `is_verified` = \'1\' THEN 1 ELSE 0 END) AS "Verified"'),
        \DB::raw('Count(id) AS "Total Records"')
    )->groupBy('beneficiary_id');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('bimsrecordreport-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('csv'),
                        Button::make('excel'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex')->title('Sn.')->searchable(false),
            Column::make('Institution'),
            Column::make('Staffs')->searchable(false),
            Column::make('Students')->searchable(false),
            Column::make('Unverified')->searchable(false),
            Column::make('Verified')->searchable(false),
            Column::make('Total Records')->searchable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'BIMSRecordReport_' . date('YmdHis');
    }
}
