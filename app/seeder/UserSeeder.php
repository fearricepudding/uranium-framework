<?php

namespace uranium\seeder;

use uranium\core\EncryptionHelper;
use uranium\model\UserModel;

class UserSeeder{
    public static function run(): void{
        $userData = [
            [
                "username" => "test",
                "email"    => "test@test.test",
                "password" =>  EncryptionHelper::generateHash("Password1!")
            ]
        ];
        $model = new UserModel();
        $model->rows = $userData;
        $model->save();
    }
}
