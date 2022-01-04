<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\MakerBundle\Doctrine\RelationOneToMany;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title', "Titre de l'article"),
            // ImageField::new('coverImage')
            //     ->setBasePath('uploads/articles')
            //     ->setUploadDir('public/uploads/articles/')
            //     ->setUploadedFileNamePattern('[randomhash].[extension]'),
            // TextareaField::new('imageFile')
            //     ->setFormType(VichImageType::class),
            TextField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex(),
            ImageField::new('coverImage')->setBasePath('/uploads/articles/')->onlyOnIndex(),
            // SlugField::new('slug', "Slug (automatique)")->setTargetFieldName('title', 'id')->hideOnIndex(),
            TextField::new('description', "Le sous-titre (facultatif)")->hideOnIndex(),
            TextEditorField::new('content', "Contenu de l'article"),
            AssociationField::new('categories'),
            AssociationField::new('keywords'),
            BooleanField::new('online', 'En ligne'),
            AssociationField::new('author'),
        ];
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
