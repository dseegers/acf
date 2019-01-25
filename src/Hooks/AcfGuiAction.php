<?php
namespace OffbeatWP\Acf\Hooks;

class AcfGuiAction {
    public function execute () {
        add_filter('acf/location/rule_types', [$this, 'locationRuleTypes']);
        add_filter('acf/location/rule_values/offbeatwp_component', [$this,'locationRulesValues']);
        add_filter('acf/location/rule_match/offbeatwp_component', [$this, 'locationRulesMatch'], 10, 3);
    }

    public function locationRuleTypes($choices)
    {
        $choices['OffbeatWP']['offbeatwp_component'] = 'Component';

        return $choices;
    }

    public function locationRulesValues($choices)
    {
        $components = offbeat('components')->get();

        if (!empty($components)) foreach($components as $componentKey => $component) {
            if (!method_exists($component, 'settings') || (!$component::supports('pagebuilder') && !$component::supports('widget') && !$component::supports('editor'))) continue;

            $componentSettings = $component::settings();
            $choices[$componentKey] = $componentSettings['name'];
        }
        
        return $choices;
    }

    public function locationRulesMatch($match, $rule, $options)
    {   
        if (!isset($options['offbeatwp_component']) || $options['offbeatwp_component'] != $rule['value']) return $match;

        return true;
    }
}