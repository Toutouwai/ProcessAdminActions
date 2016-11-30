<?php

class CopyContentToOtherField extends ProcessAdminActions {

    protected $description = 'This action copies the content from one field to another field on all pages that use the selected template.';
    protected $notes = 'This can be useful if you decide you need to split one field into two to allow different settings on different templates. It also makes it easy to move content from one field type to another one that is incompatible.';
    protected $author = 'Adrian Jones';
    protected $authorLinks = array(
        'pwforum' => '985-adrian',
        'pwdirectory' => 'adrian-jones',
        'github' => 'adrianbj',
    );

    protected function defineOptions() {
        return array(
            array(
                'name' => 'template',
                'label' => 'Template',
                'description' => 'Choose the template of the pages to copy the field',
                'type' => 'select',
                'required' => true,
                'options' => $this->templates->find("sort=name, flags!=".Template::flagSystem)->getArray()
            ),
            array(
                'name' => 'sourceField',
                'label' => 'Source Field',
                'description' => 'Choose the source field',
                'type' => 'select',
                'required' => true,
                'options' => $this->fields->find("sort=name")->getArray()
            ),
            array(
                'name' => 'destinationField',
                'label' => 'Destination Field',
                'description' => 'Choose the destination field',
                'type' => 'select',
                'required' => true,
                'options' => $this->fields->find("sort=name")->getArray()
            )
        );
    }


    protected function executeAction($options) {

        $sourceFieldName = $this->fields->get($options['sourceField'])->name;
        $destinationFieldName = $this->fields->get($options['destinationField'])->name;
        $templateName = $this->templates->get($options['template'])->name;

        foreach($this->pages->find("template=".$templateName) as $p) {
            $p->of(false);
            $p->$destinationFieldName = $p->$sourceFieldName;
            $p->save($destinationFieldName);
        }

        $this->successMessage = 'The contents of the "' . $sourceFieldName . '" field were successfully copied from the "' . $destinationFieldName . '" field on all pages with the "' . $templateName . '" template.';
        return true;

    }

}