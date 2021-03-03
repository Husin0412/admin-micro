<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="profile-image">
          <img class="img-xs rounded-circle" src="{{ session('session_user')->avatar ?? asset('assets/images/null.png') }}" alt="profile image">
          <div class="dot-indicator bg-success"></div>
        </div>
        <div class="text-wrapper">
          <p class="profile-name">{{ session('session_user')->name ?? 'name admin' }}</p>
          <p class="designation">{{ session('session_user')->profession ?? 'profession admin' }}</p>
        </div>
      </a>
    </li>
    <li class="nav-item nav-category">Main Menu</li>

    {!! isset($page) ? $page->module_sidebar(0, isset($module) ? $module : '') : '<div class="ml-4 mt-4 text-secondary ">Please load <span class="text-white"> $page </span> on Controller..</div>' !!}

  </ul>
</nav>