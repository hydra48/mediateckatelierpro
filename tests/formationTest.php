<?php
namespace App\tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Formation;

/**
 * Description of Test
 *
 * @author joueu
 */
class formationTest extends TestCase {
    //put your code here
    public function testGetDatecreationString(){
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2023-03-28"));
        $this->assertEquals("28/03/2023", $formation->getPublishedAtString());
        
    }
}
