{% extends "_template/base.html" %}

{% block title %}A2B HUB{% endblock %}

{% block body %}

  <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
      <!-- Navbar -->
      {% include ('_template/admin/navbar.php') %}
      <!-- /.navbar -->
      <!-- Main Sidebar Container -->
      {% include ('_template/admin/menu_sidebar.php') %}
      <!-- /.Main Sidebar Container -->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        {% include ('admin/search_result.php') %}
        {% include ('admin/datatable.php') %}
        {% include ('admin/add_form.php') %}
        {% include ('admin/profile.php') %}
      </div>
      <!-- /.Content Wrapper. Contains page content -->
    </div>
  </body>
{% endblock %}