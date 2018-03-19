<?php

/* database/structure/empty_table.twig */
class __TwigTemplate_6a1bb58884d9d1642aef83b3bb9aedf2806a4c6f2ad90691a59c6c8c4dac5a09 extends Twig_Template
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
        echo "<a class=\"truncate_table_anchor ajax\" href=\"sql.php";
        echo twig_escape_filter($this->env, (isset($context["tbl_url_query"]) ? $context["tbl_url_query"] : null), "html", null, true);
        echo "&amp;sql_query=";
        // line 2
        echo twig_escape_filter($this->env, (isset($context["sql_query"]) ? $context["sql_query"] : null), "html", null, true);
        echo "&amp;message_to_show=";
        echo twig_escape_filter($this->env, (isset($context["message_to_show"]) ? $context["message_to_show"] : null), "html", null, true);
        echo "\">
    ";
        // line 3
        echo (isset($context["title"]) ? $context["title"] : null);
        echo "
</a>
";
    }

    public function getTemplateName()
    {
        return "database/structure/empty_table.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  29 => 3,  23 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "database/structure/empty_table.twig", "/private/var/www/miaosha/web/phpmyadmin/templates/database/structure/empty_table.twig");
    }
}
