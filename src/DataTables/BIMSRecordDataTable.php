<?php

namespace TETFund\BIMSOnboarding\DataTables;

use TETFund\BIMSOnboarding\Models\BIMSRecord;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

use Hasob\FoundationCore\Models\Organization;

class BIMSRecordDataTable extends DataTable
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
        $dataTable = new EloquentDataTable($query);

        return $dataTable->addColumn('action', 'tetfund-bims-module::pages.b_i_m_s_records.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\BIMSRecord $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BIMSRecord $model)
    {
        if ($this->organization != null){
            return $model->newQuery()->where("organization_id", $this->organization->id);
        }
        
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    ['extend' => 'create', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
                    ['extend' => 'export', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
                    ['extend' => 'print', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
                    ['extend' => 'reset', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
                    ['extend' => 'reload', 'className' => 'btn btn-primary btn-outline btn-xs no-corner',],
                ],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'first_name_verified',
            'middle_name_verified',
            'last_name_verified',
            'name_title_verified',
            'name_suffix_verified',
            'matric_number_verified',
            'staff_number_verified',
            'email_verified',
            'phone_verified',
            'phone_network_verified',
            'bvn_verified',
            'nin_verified',
            'dob_verified',
            'gender_verified',
            'first_name_imported',
            'middle_name_imported',
            'last_name_imported',
            'name_title_imported',
            'name_suffix_imported',
            'matric_number_imported',
            'staff_number_imported',
            'email_imported',
            'phone_imported',
            'phone_network_imported',
            'bvn_imported',
            'nin_imported',
            'dob_imported',
            'gender_imported',
            'user_status',
            'user_type',
            'admin_entered_record_issues',
            'admin_entered_record_notes'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'b_i_m_s_records_datatable_' . time();
    }
}
