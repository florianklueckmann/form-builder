<?php

namespace Neos\Form\Builder\Fusion;

use Neos\Form\Core\Model\FormDefinition;
use Neos\Fusion\FusionObjects\AbstractFusionObject;

class FormPageImplementation extends AbstractFusionObject
{

    public function getPath(): string
    {
        return $this->path;
    }

    public function evaluate()
    {
        $context = $this->runtime->getCurrentContext();
        // TODO error handling if "form" is not available
        if (!isset($context['form'])) {
            return '';
        }
        /** @var FormDefinition $formDefinition */
        $formDefinition = $context['form'];

        $page = $formDefinition->createPage($this->fusionValue('identifier'), $this->getFormElementType());
        $page->setLabel($this->getLabel());
        foreach ($this->getRenderingOptions() as $optionName => $optionValue) {
            $page->setRenderingOption($optionName, $optionValue);
        }

        $this->runtime->pushContext('parentRenderable', $page);
        $this->runtime->evaluate($this->path . '/elements');
        $this->runtime->popContext();
    }

    private function getRenderingOptions(): array
    {
        return $this->fusionValue('renderingOptions');
    }

    private function getFormElementType(): string
    {
        return $this->fusionValue('formElementType');
    }

    /**
     * @return string|null
     */
    private function getLabel()
    {
        return $this->fusionValue('label');
    }
}