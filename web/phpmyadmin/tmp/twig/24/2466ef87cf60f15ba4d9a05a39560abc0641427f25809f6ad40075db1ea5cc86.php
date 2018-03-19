<?php

/* dropdown.twig */
class __TwigTemplate_c36417179064c1f6ea2105592b3d23b7628ae63b6e7567107f4e5e4ef1562f8b extends Twig_Template
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
        echo "<select name=\"";
        echo twig_escape_filter($this->env, (isset($context["select_name"]) ? $context["select_name"] : null), "html", null, true);
        echo "\" id=\"";
        echo twig_escape_filter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
        echo "\"";
        echo twig_escape_filter($this->env, (((isset($context["class"]) ? $context["class"] : null)) ? (((" class=\"" . (isset($context["class"]) ? $context["class"] : null)) . "\"")) : ("")), "html", null, true);
        echo ">
";
        // line 2
        if ( !twig_test_empty((isset($context["placeholder"]) ? $context["placeholder"] : null))) {
            // line 3
            echo "    <option value=\"\" disabled=\"disabled\"";
            // line 4
            if ( !(isset($context["selected"]) ? $context["selected"] : null)) {
                echo " selected=\"selected\"";
            }
            echo ">";
            echo twig_escape_filter($this->env, (isset($context["placeholder"]) ? $context["placeholder"] : null), "html", null, true);
            echo "</option>
";
        }
        // line 6
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["resultOptions"]) ? $context["resultOptions"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
            // line 7
            echo "<option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["option"], "value", array(), "array"), "html", null, true);
            echo "\"";
            // line 8
            echo (($this->getAttribute($context["option"], "selected", array(), "array")) ? (" selected=\"selected\"") : (""));
            echo ">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["option"], "label", array(), "array"), "html", null, true);
            echo "</option>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 10
        echo "</select>
";
    }

    public function getTemplateName()
    {
        return "dropdown.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  59 => 10,  49 => 8,  45 => 7,  41 => 6,  32 => 4,  30 => 3,  28 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "dropdown.twig", "/private/var/www/miaosha/web/phpmyadmin/templates/dropdown.twig");
    }
}
