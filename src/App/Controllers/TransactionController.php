<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, TransactionService};

class TransactionController
{
    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private TransactionService $transactionService
    ) {
    }

    public function createView()
    {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];

        echo $this->view->render("transactions/create.php");
    }

    public function create()
    {
        $this->validatorService->validateTransaction($_POST);

        $this->transactionService->create($_POST);

        redirectTo('/');
    }


    public function editView(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params['transaction']);

        if (!$transaction) {
            redirectTo('/');
        }

        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];


        echo $this->view->render('transactions/edit.php', [
            'transaction' => $transaction
        ]);
    }

    public function edit(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params['transaction']);

        if (!$transaction) {
            redirectTo('/');
        }

        $this->validatorService->validateTransaction($_POST);

        $this->transactionService->update($_POST, $transaction['id']);


        $returnTo = $_SESSION['return_to'];
        redirectTo($returnTo);
        // redirectTo($_SERVER['HTTP_REFERER']);
    }
}
