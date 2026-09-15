<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class Super extends Seeder
{
    public function run()
    {
        Try {

            // Abertura da transaction
            $this->db->transStart();

            $userModel = new UserModel();

            $user = new User([
                'email'    => 'super@super.com',
                'username' => 'leo_super',
                'password' => env('PASS_DEFAULT'),
            ]);

            $userModel->save($user);

            $user = $userModel->findById($userModel->getInsertID());

            $user->activate();

            $user->addGroup('superadmin');

            // Fecho a transaction
            $this->db->transComplete();

            echo "User super criado";

        } catch (\Throwable $th) {

            echo $th->getMessage();
        }
    }
}
