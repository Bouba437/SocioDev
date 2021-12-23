<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;

/**
 * Permet d'avoir la configuration de base d'un champ
 *
 * @param string $label
 * @param string $placeholder
 * @param array $options
 * @return array
 */
class ApplicationType extends AbstractType {
    protected function getConfiguration(string $label, string $placeholder, array $options = []) {
        return array_merge_recursive([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder,
            ]
        ], $options);
    }
}