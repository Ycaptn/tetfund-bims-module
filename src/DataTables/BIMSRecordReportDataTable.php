<?php

namespace TETFund\BIMSOnboarding\DataTables;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
                return "{$bim_record->beneficiary->full_name} - {$bim_record->beneficiary->short_name}";

            })->addColumn('Verified', function($bim_record){

                $verified_records = $bim_record["Verified"];
                $total_records = $bim_record["Total Records"];
                if ($total_records>0){
                    $pct_verified = 100 * ($verified_records/$total_records);
                    return "<center>".number_format($pct_verified,1)."%</center>";
                }

            })->addColumn('Academic Staff', function($bim_record){
                $column_value = number_format($bim_record["Academic Staff"],0);
                return "<center>{$column_value}</center>";

            })->addColumn('Non Academic', function($bim_record){
                $column_value = number_format($bim_record["Non Academic"],0);
                return "<center>{$column_value}</center>";

            })->addColumn('Student', function($bim_record){
                $column_value = number_format($bim_record["Student"],0);
                return "<center>{$column_value}</center>";

            })->addColumn('Other', function($bim_record){
                $column_value = number_format($bim_record["Other"],0);
                return "<center>{$column_value}</center>";

            })->addColumn('Total Records', function($bim_record){
                $column_value = number_format($bim_record["Total Records"],0);
                return "<center>{$column_value}</center>";

            })->rawColumns(['Verified','Academic Staff','Non Academic','Student','Other','Total Records']);
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
            \DB::raw('SUM(CASE WHEN `user_type` = \'academic\' THEN 1 ELSE 0 END) AS "Academic Staff"'),
            \DB::raw('SUM(CASE WHEN `user_type` = \'non-academic\' THEN 1 ELSE 0 END) AS "Non Academic"'),
            \DB::raw('SUM(CASE WHEN `user_type` = \'student\' THEN 1 ELSE 0 END) AS "Student"'),
            \DB::raw('SUM(CASE WHEN `user_type` = \'other\' THEN 1 ELSE 0 END) AS "Other"'),
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
            Column::make('DT_RowIndex')->title('S/N')->searchable(false),
            Column::make('Institution')->style('width:250px;'),
            Column::make('Academic Staff')->searchable(false)->style('text-align:center;'),
            Column::make('Non Academic')->searchable(false)->style('text-align:center;'),
            Column::make('Student')->searchable(false)->style('text-align:center;'),
            Column::make('Other')->searchable(false)->style('text-align:center;'),
            //Column::make('Unverified')->searchable(false),
            Column::make('Verified')->searchable(false)->style('text-align:center;'),
            Column::make('Total Records')->searchable(false)->style('text-align:center;'),
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
