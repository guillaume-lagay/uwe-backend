<?php

namespace App\DataFixtures;

use App\Entity\Component;
use App\Entity\Module;
use App\Entity\Student;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\Date;
use Faker;

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
            ->setRoles(['ROLE_STUDENT'])
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

        $faker = Faker\Factory::create('en_US');
        for ($i = 0; $i < 10; $i++) {
            $student = new  Student();
            $student->setSuperAdmin(false)
                ->setEnabled(true)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPlainPassword($faker->password)
                ->setUsername($faker->userName)
                ->setAddress($faker->address);

            $manager->persist($student);
        }

        ############## WEB PROGRAMMING ##############

        $module = new Module();
        $module->setName('Web Programming')
            ->setAcronym('WP');

        $component = new Component();
        $component->setName('Assignment 1')
            ->setCoefficient(30)
            ->setPassDate(new \DateTime('2020-01-07 08:00:00'))
            ->setModule($module);

        $manager->persist($component);

        $component = new Component();
        $component->setName('Lab Tests')
            ->setCoefficient(20)
            ->setPassDate(new \DateTime('2020-02-03 10:00:00'))
            ->setModule($module);

        $manager->persist($component);

        $component = new Component();
        $component->setName('Written Exam')
            ->setCoefficient(50)
            ->setPassDate(new \DateTime('2020-02-15 11:00:00'))
            ->setModule($module);

        $manager->persist($component);
        $manager->persist($module);

        ############## WEB DESIGN ##############

        $module = new Module();
        $module->setName('Web Design')
            ->setAcronym('WD');

        $component = new Component();
        $component->setName('Assignment 1')
            ->setCoefficient(50)
            ->setPassDate(new \DateTime('2020-01-09 08:00:00'))
            ->setModule($module);

        $manager->persist($component);

        $component = new Component();
        $component->setName('Lab Tests')
            ->setCoefficient(50)
            ->setPassDate(new \DateTime('2020-02-06 10:00:00'))
            ->setModule($module);

        $manager->persist($component);
        $manager->persist($module);


        ############## CONTENT MANAGEMENT SYSTEM ##############

        $module = new Module();
        $module->setName('Content Management System')
            ->setAcronym('CMS');

        $component = new Component();
        $component->setName('Lab Tests')
            ->setCoefficient(60)
            ->setPassDate(new \DateTime('2020-03-12 08:00:00'))
            ->setModule($module);

        $manager->persist($component);

        $component = new Component();
        $component->setName('Writen Exam')
            ->setCoefficient(40)
            ->setPassDate(new \DateTime('2020-04-24 10:00:00'))
            ->setModule($module);

        $manager->persist($component);
        $manager->persist($module);

        ############## LESPI ##############

        $module = new Module();
        $module->setName('Legal Ethical Social and Professional Issues')
            ->setAcronym('LESPI');

        $component = new Component();
        $component->setName('Assignment 1')
            ->setCoefficient(50)
            ->setPassDate(new \DateTime('2020-01-14 08:00:00'))
            ->setModule($module);

        $manager->persist($component);

        $component = new Component();
        $component->setName('Assignment 2')
            ->setCoefficient(50)
            ->setPassDate(new \DateTime('2020-02-16 14:00:00'))
            ->setModule($module);

        $manager->persist($component);
        $manager->persist($module);

        ############## WEB DEVELOPMENT FRAMEWORKS ##############

        $module = new Module();
        $module->setName('Web Development Frameworks')
            ->setAcronym('WDF');

        $component = new Component();
        $component->setName('Assignment 1')
            ->setCoefficient(100)
            ->setPassDate(new \DateTime('2020-02-11 08:00:00'))
            ->setModule($module);

        $manager->persist($component);
        $manager->persist($module);

        ############## Web Technologies ##############

        $module = new Module();
        $module->setName('Web Technologies')
            ->setAcronym('WT');

        $component = new Component();
        $component->setName('Assignment 1')
            ->setCoefficient(50)
            ->setPassDate(new \DateTime('2020-02-14 08:00:00'))
            ->setModule($module);

        $manager->persist($component);

        $component = new Component();
        $component->setName('Assignment 2')
            ->setCoefficient(50)
            ->setPassDate(new \DateTime('2020-03-28 10:00:00'))
            ->setModule($module);

        $manager->persist($component);
        $manager->persist($module);

        ############## FLUSHING ##############

        $manager->flush();
    }
}
