<?php

/* database/create_table.twig */
class __TwigTemplate_9b453db8e86e0149819699e94f5089f88f07464c85bf0fee708bcf3b2b884d7c extends Twig_Template
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
        echo "<form id=\"create_table_form_minimal\" method=\"post\" action=\"tbl_create.php\" class=\"lock-page\">
    <fieldset>
        <legend>
        ";
        // line 4
        if (PMA\libraries\Util::showIcons("ActionLinksMode")) {
            // line 5
            echo PMA\libraries\Util::getImage("b_table_add.png");
        }
        // line 7
        echo "        ";
        echo _gettext("Create table");
        // line 8
        echo "        </legend>
        ";
        // line 9
        echo PMA\libraries\URL::getHiddenInputs((isset($context["db"]) ? $context["db"] : null));
        echo "
        <div class=\"formelement\">
            ";
        // line 11
        echo _gettext("Name");
        echo ":
            <input type=\"text\" name=\"table\" maxlength=\"64\" size=\"30\" required=\"required\" />
        </div>
        <div class=\"formelement\">
            ";
        // line 15
        echo _gettext("Number of columns");
        echo ":
            <input type=\"number\" min=\"1\" name=\"num_fields\" value=\"4\" required=\"required\" />
        </div>
        <div class=\"clearfloat\"></div>
    </fieldset>
    <fieldset class=\"tblFooters\">
        <input type=\"submit\" value=\"";
        // line 21
        echo _gettext("Go");
        echo "\" />
    </fieldset>
</form>
";
    }

    public function getTemplateName()
    {
        return "database/create_table.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  56 => 21,  47 => 15,  40 => 11,  35 => 9,  32 => 8,  29 => 7,  26 => 5,  24 => 4,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "database/create_table.twig", "/private/var/www/miaosha/web/phpmyadmin/templates/database/create_table.twig");
    }
}
