<?php

/* columns_definitions/column_type.twig */
class __TwigTemplate_19dc4afe865aab5471c50a15408213fc7232a85a185d5c059d76b0b24444c50a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<select class=\"column_type\"
    name=\"field_type[";
        // line 2
        echo twig_escape_filter($this->env, (isset($context["columnNumber"]) ? $context["columnNumber"] : null), "html", null, true);
        echo "]\"
    id=\"field_";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["columnNumber"]) ? $context["columnNumber"] : null), "html", null, true);
        echo "_";
        echo twig_escape_filter($this->env, ((isset($context["ci"]) ? $context["ci"] : null) - (isset($context["ci_offset"]) ? $context["ci_offset"] : null)), "html", null, true);
        echo "\"";
        // line 4
        if (($this->getAttribute((isset($context["columnMeta"]) ? $context["columnMeta"] : null), "column_status", array(), "array", true, true) &&  !$this->getAttribute($this->getAttribute((isset($context["columnMeta"]) ? $context["columnMeta"] : null), "column_status", array(), "array"), "isEditable", array(), "array"))) {
            // line 5
            echo "disabled=\"disabled\"";
        }
        // line 6
        echo ">
    ";
        // line 7
        echo PMA\libraries\Util::getSupportedDatatypes(true, (isset($context["type_upper"]) ? $context["type_upper"] : null));
        echo "
</select>
";
    }

    public function getTemplateName()
    {
        return "columns_definitions/column_type.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  39 => 7,  36 => 6,  33 => 5,  31 => 4,  26 => 3,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "columns_definitions/column_type.twig", "/private/var/www/miaosha/web/phpmyadmin/templates/columns_definitions/column_type.twig");
    }
}
