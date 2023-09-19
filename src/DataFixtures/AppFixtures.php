<?php

namespace App\DataFixtures;

use App\Entity\Shoe;
use App\Entity\Cupboard;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    // Defines reference names for instances of Cupboard
    private const TEST_CUPBOARD_1 = 'test-cupboard-1';
    private const TEST_CUPBOARD_2 = 'test-cupboard-2';

    /**
     * Generates initialization data for cupboards : [title, cupboard_reference]
     * @return \\Generator
     */
    private static function getCupboardsData()
    {
        yield ["Test Cupboard 1", self::TEST_CUPBOARD_1];
        yield ["Test Cupboard 2", self::TEST_CUPBOARD_2];
    }

    /**
     * Generates initialization data for shoes: [brand, model, cupboard_reference]
     * @return \\Generator
     */
    private static function getShoesData()
    {
        yield ["Kalenji", "", self::TEST_CUPBOARD_1];
        yield ["Asics", "Tarbuco 11", self::TEST_CUPBOARD_1];
    }

    public function load(ObjectManager $manager)
    {
        // $this->loadCupboards();
        // $this->loadShoes();
        $inventoryRepo = $manager->getRepository(Cupboard::class);

        foreach (self::getCupboardsData() as [$name, $cupboardReference]) {
            $cupboard = new Cupboard();
            $cupboard->setName($name);
            $manager->persist($cupboard);
            $manager->flush();

            // Once the Cupboard instance has been saved to DB
            // it has a valid ID generated by Doctrine, and can thus
            // be saved as a future reference
            $this->addReference($cupboardReference, $cupboard);
        }

        foreach (self::getShoesData() as [$brand, $model, $cupboardReference]) {
            // Create new Shoes
            $shoe = new Shoe();
            $shoe->setBrand($brand);
            $shoe->setModel($model);
            // Put the Shoes in its Cupboard
            $cupboard = $this->getReference($cupboardReference);
            $cupboard->addShoe($shoe);

            // Requires ORM\OneToMany attribute on Cupboard::shoes has "cascade: ['persist']"
            $manager->persist($cupboard);
        }
        $manager->flush();
    }
}