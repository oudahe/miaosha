<?php

/* database/structure/print_view_data_dictionary_link.twig */
class __TwigTemplate_9b8e536920f05acd6eb0febe0bf52d7c9fe6573f3fa298770aeea6246e7636d2 extends Twig_Template
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
        echo "<p class=\"print_ignore\">
    <a href=\"#\" id=\"printView\">
        ";
        // line 3
        echo PMA\libraries\Util::getIcon("b_print.png", _gettext("Print"), true);
        echo "
    </a>
    <a href=\"db_datadict.php";
        // line 5
        echo twig_escape_filter($this->env, (isset($context["url_query"]) ? $context["url_query"] : null), "html", null, true);
        echo "\" target=\"print_view\">
        ";
        // line 6
        echo PMA\libraries\Util::getIcon("b_tblanalyse.png", _gettext("Data dictionary"), true);
        echo "
    </a>
</p>
";
    }

    public function getTemplateName()
    {
        return "database/structure/print_view_data_dictionary_link.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  32 => 6,  28 => 5,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "database/structure/print_view_data_dictionary_link.twig", "/private/var/www/miaosha/web/phpmyadmin/templates/database/structure/print_view_data_dictionary_link.twig");
    }
}
