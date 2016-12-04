<?php

class DeleteUnusedFields extends ProcessAdminActions {

    protected $description = 'Deletes fields that are not used by any templates.';
    protected $notes = 'Shows a list of unused fields with checkboxes to select those to delete.';
    protected $author = 'Adrian Jones';
    protected $authorLinks = array(
        'pwforum' => '985-adrian',
        'pwdirectory' => 'adrian-jones',
        'github' => 'adrianbj',
    );

    protected function defineOptions() {

        $fieldOptions = array();
        foreach($this->fields as $field) {
            if ($field->flags & Field::flagSystem || $field->flags & Field::flagPermanent) continue;
            if(count($field->getFieldgroups()) === 0) $fieldOptions[$field->id] = $field->label ? $field->name . ' (' . $field->label . ')' : $field->name;
        }

        return array(
            array(
                'name' => 'fields',
                'label' => 'Fields',
                'description' => 'Select the fields you want to delete',
                'notes' => 'Note that all fields listed are not used by any templates and should therefore be safe to delete',
                'type' => 'checkboxes',
                'options' => $fieldOptions,
                'required' => true
            )
        );

    }


    protected function executeAction($options) {

        foreach($options['fields'] as $field_id) {
            $field = $this->fields->get($field_id);
            $this->fields->delete($field);
        }
        $count = count($options['fields']);
        $this->successMessage = $count . ' field' . _n('', 's', $count) . ' ' . _n('was', 'were', $count) . ' successfully deleted';
        return true;

    }

}