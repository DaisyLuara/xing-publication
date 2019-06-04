<?php

namespace App\Providers;

use App\Exports\CompanyExport;
use App\Exports\ContractCostExport;
use App\Exports\ContractExport;
use App\Exports\ContractHistoryExport;
use App\Exports\ContractRemindExport;
use App\Exports\ContractRevenueExport;
use App\Exports\CouponExport;
use App\Exports\DemandApplicationExport;
use App\Exports\DemandModifyExport;
use App\Exports\ErpLocationExport;
use App\Exports\ErpLocationProductExport;
use App\Exports\ErpProductExport;
use App\Exports\ErpWarehouseChangeExport;
use App\Exports\ErpWarehouseExport;
use App\Exports\InvoiceCompanyExport;
use App\Exports\InvoiceExport;
use App\Exports\InvoiceHistoryExport;
use App\Exports\InvoiceReceiptExport;
use App\Exports\MarketingExport;
use App\Exports\MarketingTopExport;
use App\Exports\OldMarketingExport;
use App\Exports\PaymentExport;
use App\Exports\PaymentHistoryExport;
use App\Exports\PaymentPayeeExport;
use App\Exports\PersonRewardExport;
use App\Exports\PlayTimesExport;
use App\Exports\PointDailyAverageExport;
use App\Exports\PointExport;
use App\Exports\ProjectByPointExport;
use App\Exports\ProjectExport;
use App\Exports\TeamProjectExport;
use Illuminate\Support\ServiceProvider;

class ExcelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('point', function ($app) {
            return new PointExport($app->request);
        });

        $this->app->bind('marketing', function ($app) {
            return new MarketingExport($app->request);
        });

        $this->app->bind('project', function ($app) {
            return new ProjectExport($app->request);
        });

        $this->app->bind('daily_average', function ($app) {
            return new PointDailyAverageExport($app->request);
        });

        $this->app->bind('project_point', function ($app) {
            return new ProjectByPointExport($app->request);
        });

        $this->app->bind('marketing_top', function ($app) {
            return new MarketingTopExport($app->request);
        });

        $this->app->bind('person_reward', function ($app) {
            return new PersonRewardExport($app->request);
        });

        $this->app->bind('coupon', function ($app) {
            return new CouponExport($app->request);
        });

        $this->app->bind('team_project', function ($app) {
            return new TeamProjectExport($app->request);
        });

        $this->app->bind('contract', function ($app) {
            return new ContractExport($app->request);
        });

        $this->app->bind('remind_contract', function ($app) {
            return new ContractRemindExport($app->request);
        });

        $this->app->bind('contract_cost', function ($app) {
            return new ContractCostExport($app->request);
        });

        $this->app->bind('contract_history', function ($app) {
            return new ContractHistoryExport($app->request);
        });

        $this->app->bind('invoice', function ($app) {
            return new InvoiceExport($app->request);
        });

        $this->app->bind('invoice_company', function ($app) {
            return new InvoiceCompanyExport($app->request);
        });

        $this->app->bind('invoice_receipt', function ($app) {
            return new InvoiceReceiptExport($app->request);
        });

        $this->app->bind('invoice_history', function ($app) {
            return new InvoiceHistoryExport($app->request);
        });

        $this->app->bind('payment', function ($app) {
            return new PaymentExport($app->request);
        });

        $this->app->bind('payment_payee', function ($app) {
            return new PaymentPayeeExport($app->request);
        });

        $this->app->bind('payment_history', function ($app) {
            return new PaymentHistoryExport($app->request);
        });

        $this->app->bind('demand_application', function ($app) {
            return new DemandApplicationExport($app->request);
        });

        $this->app->bind('demand_modify', function ($app) {
            return new DemandModifyExport($app->request);
        });

        $this->app->bind('location_product', function ($app) {
            return new ErpLocationProductExport($app->request);
        });

        $this->app->bind('erp_warehouse_change', function ($app) {
            return new ErpWarehouseChangeExport($app->request);
        });

        $this->app->bind('erp_warehouse', function ($app) {
            return new ErpWarehouseExport($app->request);
        });

        $this->app->bind('erp_location', function ($app) {
            return new ErpLocationExport($app->request);
        });

        $this->app->bind('erp_product', function ($app) {
            return new ErpProductExport($app->request);
        });

        $this->app->bind('company', function ($app) {
            return new CompanyExport($app->request);
        });

        $this->app->bind('play_times', function ($app) {
            return new PlayTimesExport($app->request);
        });

        $this->app->bind('contract_revenue', function ($app) {
            return new ContractRevenueExport($app->request);
        });
    }
}
