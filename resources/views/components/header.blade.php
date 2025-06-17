<nav class="navbar navbar-expand-lg bg-info">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('home') }}">Book Store</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        @if (Auth::check())
          @if (Auth::user()->is_admin)
            <li class="nav-book">
              <a class="nav-link active" aria-current="page" href="{{ route('books.index') }}">Book</a>
            </li>
            <li class="nav-book">
              <a class="nav-link" href="{{ route('invoice.display') }}">Invoice</a>
            </li>
          @else
            <li class="nav-book">
              <a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a>
            </li>
            <li class="nav-book">
              <a class="nav-link" href="{{ route('invoices.index') }}">Invoice</a>
            </li>
            <li class="nav-book">
              <a class="nav-link" href="{{ route('carts.index') }}">Cart</a>
            </li>
          @endif
          <li class="nav-book">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="nav-link btn btn-link text-decoration-none"
                style="padding: 0.5rem 1rem; color: inherit;">
                Logout
              </button>
            </form>
          </li>
        @else
          <li class="nav-book">
            <a class="nav-link" href="{{ route('login') }}">Login</a>
          </li>
        @endif
      </ul>

      <form class="d-flex" method="GET"
            action="{{ Auth::check() && Auth::user()->is_admin ? route('books.index') : route('books.catalog') }}">
        <input class="form-control me-2" type="search" name="search" placeholder="Search book title..." 
          value="{{ request('search') }}" aria-label="Search">
        <button class="btn btn-outline-light" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
