<?php
/**
 * Created by PhpStorm.
 * User: giorgi
 * Date: 4/1/18
 * Time: 12:02 PM
 */

namespace App\Classifiers;

class BaseClassifier
{
    public function rules()
    {
        $categories = [
            'basic' => 'basic expenses',
            'finances',
            'edukacja',
            'cash',
            'internal transfer',
            'worknbusiness' => 'work and business',
            //'bank and insurance',
        ];

        return [
            'homenbills' => [
                [
                    'type' => 'in:online_banking',
                    'bank' => 'in:tbcbank',
                    'description' => 'regex:/([a-zA-Z0-9]+);([0-9]){9};(თანხა:([0-9.]+))/'
                ]
            ],
        ];
    }
}