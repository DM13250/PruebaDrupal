<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* modules/custom/usuarios_diego/templates/usuarios-diego-list.html.twig */
class __TwigTemplate_6edffc1792a17688fad4d716361ab00d extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 15
        echo "<div id=\"usuarios-diego-container\" class=\"usuarios-diego-container\">
  ";
        // line 17
        echo "  
  ";
        // line 19
        echo "  <div class=\"search-form-wrapper\">
    ";
        // line 20
        if (($context["search_form"] ?? null)) {
            // line 21
            echo "      ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["search_form"] ?? null), 21, $this->source), "html", null, true);
            echo "
    ";
        } else {
            // line 23
            echo "      <div class=\"search-form-manual\">
        <form action=\"";
            // line 24
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->getPath("usuarios_diego.list"));
            echo "\" method=\"get\" id=\"user-search-form\" class=\"usuario-search-form\">
          <div class=\"form-item\">
            <label for=\"edit-search-name\">Nombre</label>
            <input type=\"text\" id=\"edit-search-name\" name=\"search_name\" class=\"form-text\" value=\"";
            // line 27
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["search_name"] ?? null), 27, $this->source), "html", null, true);
            echo "\" />
          </div>
          <div class=\"form-item\">
            <label for=\"edit-search-surname\">Apellidos</label>
            <input type=\"text\" id=\"edit-search-surname\" name=\"search_surname\" class=\"form-text\" value=\"";
            // line 31
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["search_surname"] ?? null), 31, $this->source), "html", null, true);
            echo "\" />
          </div>
          <div class=\"form-item\">
            <label for=\"edit-search-email\">Correo Electrónico</label>
            <input type=\"text\" id=\"edit-search-email\" name=\"search_email\" class=\"form-text\" value=\"";
            // line 35
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["search_email"] ?? null), 35, $this->source), "html", null, true);
            echo "\" />
          </div>
          <div class=\"form-actions\">
            <input type=\"submit\" id=\"edit-submit\" value=\"Buscar\" class=\"form-submit\" />
          </div>
        </form>
      </div>
    ";
        }
        // line 43
        echo "  </div>

  ";
        // line 46
        echo "  <div class=\"user-list-table-container\">
    <table";
        // line 47
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => "usuario-table"], "method", false, false, true, 47), 47, $this->source), "html", null, true);
        echo ">
      <thead>
        <tr>
          ";
        // line 50
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["header"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["cell"]) {
            // line 51
            echo "            <th";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["cell"], "attributes", [], "any", false, false, true, 51), "addClass", [0 => "usuario-table-header"], "method", false, false, true, 51), 51, $this->source), "html", null, true);
            echo ">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["cell"], "data", [], "any", false, false, true, 51), 51, $this->source), "html", null, true);
            echo "</th>
          ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['cell'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 53
        echo "        </tr>
      </thead>
      <tbody>
        ";
        // line 56
        if (($context["rows"] ?? null)) {
            // line 57
            echo "          ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["rows"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                // line 58
                echo "            <tr>
              ";
                // line 59
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["row"], "data", [], "any", false, false, true, 59));
                foreach ($context['_seq'] as $context["_key"] => $context["cell"]) {
                    // line 60
                    echo "                <td>";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($context["cell"], 60, $this->source), "html", null, true);
                    echo "</td>
              ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['cell'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 62
                echo "            </tr>
          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 64
            echo "        ";
        } else {
            // line 65
            echo "          <tr>
            <td colspan=\"";
            // line 66
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_length_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["header"] ?? null), 66, $this->source)), "html", null, true);
            echo "\" class=\"empty\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["empty"] ?? null), 66, $this->source), "html", null, true);
            echo "</td>
          </tr>
        ";
        }
        // line 69
        echo "      </tbody>
    </table>
    
    ";
        // line 73
        echo "    <div class=\"pager-container\">
      <h4 class=\"pager-heading\">Página ";
        // line 74
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, (($context["current_page"] ?? null) + 1), "html", null, true);
        echo " de ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["total_pages"] ?? null), 74, $this->source), "html", null, true);
        echo "</h4>
      
      <nav class=\"pager\" role=\"navigation\" aria-labelledby=\"pagination-heading\">
        <h4 id=\"pagination-heading\" class=\"visually-hidden\">";
        // line 77
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Paginación"));
        echo "</h4>
        
        ";
        // line 80
        echo "        ";
        if (($context["pager"] ?? null)) {
            // line 81
            echo "          <div class=\"usuarios-diego-pager-wrapper\">
            ";
            // line 82
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["pager"] ?? null), 82, $this->source), "html", null, true);
            echo "
          </div>
        ";
        }
        // line 85
        echo "      </nav>
    </div>
  </div>
</div>

<style>
  /* Estilos básicos para la paginación */
  .pager-container {
    margin-top: 20px;
    text-align: center;
  }
  
  .pager-heading {
    margin-bottom: 10px;
    font-weight: bold;
  }
</style> ";
    }

    public function getTemplateName()
    {
        return "modules/custom/usuarios_diego/templates/usuarios-diego-list.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  200 => 85,  194 => 82,  191 => 81,  188 => 80,  183 => 77,  175 => 74,  172 => 73,  167 => 69,  159 => 66,  156 => 65,  153 => 64,  146 => 62,  137 => 60,  133 => 59,  130 => 58,  125 => 57,  123 => 56,  118 => 53,  107 => 51,  103 => 50,  97 => 47,  94 => 46,  90 => 43,  79 => 35,  72 => 31,  65 => 27,  59 => 24,  56 => 23,  50 => 21,  48 => 20,  45 => 19,  42 => 17,  39 => 15,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/custom/usuarios_diego/templates/usuarios-diego-list.html.twig", "C:\\xampp\\htdocs\\my_drupal_project\\web\\modules\\custom\\usuarios_diego\\templates\\usuarios-diego-list.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 20, "for" => 50);
        static $filters = array("escape" => 21, "length" => 66, "t" => 77);
        static $functions = array("path" => 24);

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
                ['escape', 'length', 't'],
                ['path']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
