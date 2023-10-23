<?php

namespace TETFund\BIMSOnboarding\DataTables;

use TETFund\BIMSOnboarding\Models\BIMSKnownUser;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

use Hasob\FoundationCore\Models\Organization;

class BIMSKnownUserDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'tetfund-bims-module::pages.b_i_m_s_known_users.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\BIMSKnownUser $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BIMSKnownUser $model)
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
            'beneficiary_id',
            'bims_db_row_id',
            'bims_id',
            'first_name',
            'middle_name',
            'last_name',
            'type',
            'email',
            'gender',
            'dob',
            'photo',
            'institution_meta_data',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'b_i_m_s_known_users_datatable_' . time();
    }
}
