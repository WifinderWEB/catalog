<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            
            new FOS\UserBundle\FOSUserBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new FM\ElfinderBundle\FMElfinderBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Catalog\CatalogBundle\CatalogCatalogBundle(),
            new Catalog\ContentBundle\CatalogContentBundle(),
            new Catalog\TagBundle\CatalogTagBundle(),
            new Catalog\ImageGalleryBundle\CatalogImageGalleryBundle(),
            new Catalog\VideoGalleryBundle\CatalogVideoGalleryBundle(),
            new Catalog\FileGalleryBundle\CatalogFileGalleryBundle(),
            new Catalog\UserBundle\CatalogUserBundle(),
//            new Admingenerator\UserBundle\AdmingeneratorUserBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\AopBundle\JMSAopBundle(),
            new Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
            new Catalog\ProjectBundle\CatalogProjectBundle(),
            new Api\CatalogBundle\ApiCatalogBundle(),
            new Catalog\StockBundle\CatalogStockBundle(),
            new Catalog\OrderBundle\CatalogOrderBundle(),
            new Catalog\CategoryBundle\CatalogCategoryBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
