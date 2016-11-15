<?php

namespace Catalog\ContentBundle\Form\Type\Content;

use Catalog\CategoryBundle\Entity\Repository\CategoryRepository;
use Catalog\ContentBundle\Entity\Repository\ContentRepository;
use Catalog\ContentBundle\Form\ContentMetaType;
use Catalog\ContentBundle\Form\ContentParametersType;
use Symfony\Component\Form\FormBuilderInterface;
use Admingenerated\CatalogContentBundle\Form\BaseContentType\EditType as BaseEditType;
use Catalog\CatalogBundle\Entity\Repository\CatalogRepository;
use Catalog\TagBundle\Entity\Repository\TagRepository;
use Catalog\ImageGalleryBundle\Entity\Repository\ImageCategoryRepository;
use Catalog\VideoGalleryBundle\Entity\Repository\VideoCategoryRepository;
use Catalog\FileGalleryBundle\Entity\Repository\FileRepository;
use Catalog\ContentBundle\Form\ContentSaleType;

/**
 * EditType
 */
class EditType extends BaseEditType {

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder->add('title', 'text', array(
            'required' => true,
            'label' => 'Title',
            'translation_domain' => 'Admin',
            'attr' => array('class' => 'title_box')
        ));
        $builder->add('alias', 'text', array(
            'required' => true,
            'label' => 'Alias',
            'translation_domain' => 'Admin',
            'attr' => array('class' => 'alias_box')
        ));
        if($builder->getData()->getShowEditorAnons()){
            $builder->add('anons', 'ckeditor', array(
                'required' => false,
                'label' => 'Anons',
                'translation_domain' => 'Admin'
            ));
        }
        else{
            $builder->add('anons', 'textarea', array(
                'required' => false,
                'label' => 'Anons',
                'translation_domain' => 'Admin'
            ));
        }
        $builder->add('show_editor_anons', 'checkbox', array(  
            'required' => false,  
            'label' => 'Show editor anons',  
            'translation_domain' => 'Admin'
        ));
        if($builder->getData()->getShowEditorContent()){
            $builder->add('content', 'ckeditor', array(
                'required' => false,
                'label' => 'Content',
                'translation_domain' => 'Admin'
            ));
        }
        else{
            $builder->add('content', 'textarea', array(
                'required' => false,
                'label' => 'Content',
                'translation_domain' => 'Admin'
            ));
        }
        $builder->add('show_editor_content', 'checkbox', array(  
            'required' => false,  
            'label' => 'Show editor content',  
            'translation_domain' => 'Admin'
        ));
        $builder->add('image', 'file', array(
            'required' => false,
            'label' => 'Image',
            'translation_domain' => 'Admin'
        ));
        $builder->add('title_image', 'text', array(
            'required' => false,
            'label' => 'Title image',
            'translation_domain' => 'Admin'
        ));
        $builder->add('alt_image', 'text', array(
            'required' => false,
            'label' => 'Alt image',
            'translation_domain' => 'Admin'
        ));
        $builder->add('big_image', 'file', array(
            'required' => false,
            'label' => 'Big image',
            'translation_domain' => 'Admin',
        ));
        $builder->add('title_big_image', 'text', array(
            'required' => false,
            'label' => 'Title image',
            'translation_domain' => 'Admin'
        ));
        $builder->add('alt_big_image', 'text', array(
            'required' => false,
            'label' => 'Alt image',
            'translation_domain' => 'Admin'
        ));
//        $builder->add('catalogs', 'entity', array(
//            'required' => false,
//            'multiple' => true,
//            'em' => 'default',
//            'translation_domain' => 'Admin',
//            'class' => 'CatalogCatalogBundle:Catalog',
//            'attr' => array('class' => 'multiselect'),
//            'query_builder' => function(CatalogRepository $er){
//                return $er->getListCategory();
//            },
//        ));
        $builder->add('tags', 'entity', array(
            'required' => false,
            'multiple' => true,
            'em' => 'default',
            'class' => 'CatalogTagBundle:Tag',
            'translation_domain' => 'Admin',
            'attr' => array('class' => 'multiselect'),
            'query_builder' => function(TagRepository $er){
                return $er->getListTags();
            },
        ));
        $builder->add('image_gallery', 'entity', array(
            'required' => false,
            'multiple' => true,
            'em' => 'default',
            'class' => 'CatalogImageGalleryBundle:ImageCategory',
            'translation_domain' => 'Admin',
            'attr' => array('class' => 'multiselect'),
            'query_builder' => function(ImageCategoryRepository $er){
                return $er->getListImages();
            },
        ));
        $builder->add('video_gallery', 'entity', array(
            'required' => false,
            'multiple' => true,
            'em' => 'default',
            'class' => 'CatalogVideoGalleryBundle:VideoCategory',
            'translation_domain' => 'Admin',
            'attr' => array('class' => 'multiselect'),
            'query_builder' => function(VideoCategoryRepository $er){
                return $er->getListVideos();
            },
        ));
        $builder->add('delete_image', 'checkbox', array(
            'label'     => 'Delete image?',
            'required'  => false,
            'translation_domain' => 'Admin',
        ));
        $builder->add('delete_big_image', 'checkbox', array(
            'label'     => 'Delete image?',
            'required'  => false,
            'translation_domain' => 'Admin',
        ));
        $builder->add('files', 'entity', array(
            'required' => false,
            'multiple' => true,
            'em' => 'default',
            'class' => 'CatalogFileGalleryBundle:File',
            'translation_domain' => 'Admin',
            'attr' => array('class' => 'multiselect'),
            'query_builder' => function(FileRepository $er){
                return $er->getListFiles();
            },
        ));

        $data = $builder->getData();
        $id = $data->getId();
        $builder->add('related', 'entity', array(
            'required' => false,
            'multiple' => true,
            'em' => 'default',
            'translation_domain' => 'Admin',
            'class' => 'CatalogContentBundle:Content',
            'property' => 'TitleWithArticle',
            'query_builder' => function(ContentRepository $er) use ($id) {
                return $er->getContentWithoutSelf($id);
            },
            'attr' => array('class' => 'multiselect')
        ));
        $builder->add('sale', new ContentSaleType());
        $builder->add('more', new \Catalog\ContentBundle\Form\MoreFieldType($builder->getData()));
        $builder->add('meta', new ContentMetaType());
        $builder->add('category', 'entity', array(
            'class' => 'CatalogCategoryBundle:Category',
            'required' => false,
            'query_builder' => function(CategoryRepository $er) {
                return $er->getListCategory();
            },
        ));
        if($data->getCategory()){
            $builder->add('group_parameters', new ContentParametersType($data));
        }
    }

}
