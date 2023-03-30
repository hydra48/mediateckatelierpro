<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Validations;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Formation;
use DateTime;

/**
 * Description of formationValidationsTest
 *
 * @author joueu
 */
class formationValidationsTest extends KernelTestCase{
    //put your code here
    public function getFormation(): Formation{
        return (new Formation())
        ->setTitle('Une formation')
        ->setPublishedAt(new DateTime("2023/03/09"));
               
                
    }
    
    public function testnonvalidedateformation(){
        $formation = $this->getFormation()->setPublishedAt(new DateTime("2025/08/10"));
        $this->assertErrors($formation,1, "devrait echouer");
    }
    public function assertErrors(Formation $formation, int $nbErreursAttendues, string $message=""){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues,$error,$message);
    }
    
}
