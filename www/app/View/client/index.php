{% extends "_template/base.html" %}

{% block title %}A2B HUB{% endblock %}

{% block css %}
  {{ getCss()|raw }}
{% endblock %}

{% block body %}

  <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
      <!-- Navbar -->
      {% include ('_template/client/navbar.php') %}
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      {% include ('_template/client/menu_sidebar.php') %}
      <!-- /.Main Sidebar Container -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">

        {% set url = getCurrentPage() %}

        {% if '/client' in url %}

        {% include ('client/search_filter.php') %}
          {% include ('client/datatable.php') %}

        {% elseif '/client/profile' in url %}

          {% include ('client/profile.php') %}

        {% elseif '/client/userLogs' in url %}

          {% include ('client/user_logs.php') %}

        {% else %}

          {% include ('_template/404.php') %}

        {% endif %}
        
        {% include ('client/add_form.php') %}
        
      </div>
      <!-- /.Content Wrapper. Contains page content -->
    </div>
  </body>

{% endblock %}

{% block js %}
  {{ getJs()|raw }}
{% endblock %}