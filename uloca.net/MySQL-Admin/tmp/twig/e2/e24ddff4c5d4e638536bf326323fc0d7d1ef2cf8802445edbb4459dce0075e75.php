<?php

/* table/search/table_header.twig */
class __TwigTemplate_5c9f87aa33490760eba7e1eefb5bc848d6d0fe2f56adc4c572dd30aed0b3b07f extends Twig_Template
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
        echo "<thead>
    <tr>
        ";
        // line 3
        if (($context["geom_column_flag"] ?? null)) {
            // line 4
            echo "            <th>";
            echo _gettext("Function");
            echo "</th>
        ";
        }
        // line 6
        echo "        <th>";
        echo _gettext("Column");
        echo "</th>
        <th>";
        // line 7
        echo _gettext("Type");
        echo "</th>
        <th>";
        // line 8
        echo _gettext("Collation");
        echo "</th>
        <th>";
        // line 9
        echo _gettext("Operator");
        echo "</th>
        <th>";
        // line 10
        echo _gettext("Value");
        echo "</th>
    </tr>
</thead>
";
    }

    public function getTemplateName()
    {
        return "table/search/table_header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  48 => 10,  44 => 9,  40 => 8,  36 => 7,  31 => 6,  25 => 4,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "table/search/table_header.twig", "/uloca22/www/MySQL-Admin/templates/table/search/table_header.twig");
    }
}
