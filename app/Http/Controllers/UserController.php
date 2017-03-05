<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Exceptions\HttpException;

class UserController extends AbstractController
{
    public function index()
    {
        return $this->view('pages.user-index');
    }

    /**
     * [detail description]
     * @param  [type] $request  [description]
     * @param  [type] $response [description]
     * @param  [type] $params   [description]
     * @return [type]           [description]
     */
    public function detail($request, $response, $params)
    {
        $model = $this->getModel($params['id']);

        return $this->view('pages.user-detail', ['model' => $model]);
    }

    private function getModel($id)
    {
        $model = User::find($id);
        if (!$model) {
            throw new HttpException(404, 'User not found');
        }

        return $model;
    }
}
