<?php

namespace App\DataFixtures;

use App\Entity\Component;
use App\Entity\Module;
use App\Entity\Student;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        ############## LOADING USERS ##############

        $user = new Student();
        $user->setAddress('UWE Frenchay Campus')
            ->setFirstName('Dorian')
            ->setLastName('LAGAY')
            ->setEmail('glagay@localhost.fr')
            ->setPlainPassword('glagay')
            ->setEnabled(true)
            ->setRoles(['ROLE_USER'])
            ->setSuperAdmin(false)
            ->setUsername('glagay');

        $manager->persist($user);

        $admin = new User();
        $admin->setAddress('UWE Frenchay Campus')
            ->setFirstName('admin')
            ->setLastName('admin')
            ->setEmail('admin@localhost.fr')
            ->setPlainPassword('admin')
            ->setEnabled(true)
            ->setSuperAdmin(true)
            ->setUsername('admin');

        $manager->persist($admin);

        ############## LOADING MODULES ##############

        $modules = ['Web Programming' => 'WP', 'Web Design' => 'WD', 'Content Management System' => 'CMS', 'Legal Ethical Social and Professional Issues' => 'LESPI',
            'Web Development Frameworks' => 'WDF', 'Web Technologies' => 'WT'];

        foreach ($modules as $name => $acronym) {
            $module = new Module();
            $module->setName($name)
                ->setAcronym($acronym);

            $manager->persist($module);
        }

        ############## LOADING COMPONENTS ##############

        $components = ['Assignment 1' => ['passDate' => '2020-01-07 08:00:00', 'coef' => 30],
            'Lab Tests' => ['passDate' => '2020-02-01 10:00:00', 'coef' => 20] ];

        foreach ($components as $name => $datas) {
            $component = new Component();
            $component->setName($name)
                ->setPassDate(new \DateTime($datas['passDate']))
                ->setCoefficient($datas['coef']);

            $manager->persist($component);
        }

        ############## FLUSHING ##############

        $manager->flush();
    }
}
