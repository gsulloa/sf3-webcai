<?php
/**
 * Created by PhpStorm.
 * User: gsull
 * Date: 13-12-2015
 * Time: 1:50
 */
namespace Cai\WebBundle\DataFixtures\ORM;

use Cai\WebBundle\Entity\Contacto;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadContactoData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $contacto = new Contacto();
        $contacto->setDescripcion('El Centro de Alumnos de Ingeniería (CAi) es una institución fundada en 1904 con el fin de representar a los alumnos de la Escuela de Ingeniería de la Pontificia Universidad Católica de Chile. En esta página encontrarás toda la información respecto a su actividad.')
            ->setDireccion('Av. Vicuña Mackenna #4860, Macul')
            ->setFacebook('caiuc')
            ->setLema('Porque juntos somos Escuela')
            ->setMail('contacto@cai.cl')
            ->setTelefono('+56 22 354 4759')
            ->setTwitter('caipuc')
            ->setVimeo('caiuc')
            ->setYoutube('caiuctv');
        $manager->persist($contacto);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}