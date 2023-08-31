<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SimpleCms</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
  <style>
    :root {
      /* Futuristic colors */
      --bs-primary: #00ff00; /* Neon green */
      --bs-secondary: #ff00ff; /* Neon pink */
      --bs-info: #00ffff; /* Neon cyan */
      --bs-warning: #ffaa00; /* Neon orange */
      --bs-danger: #ff0000; /* Neon red */
      --bs-light: #f0f0f0; /* Light gray */
      --bs-dark: #101010; /* Dark gray */

      /* Futuristic font */
      --bs-font-sans-serif: "Orbitron", sans-serif;

      /* Futuristic button styles */
      --bs-btn-border-width: 2px;
      --bs-btn-border-radius: 5px;
      --bs-btn-transition: all 0.3s ease;

      /* Futuristic link styles */
      --bs-link-hover-color: var(--bs-primary);
      --bs-link-hover-decoration: underline;
    }
  </style>
</head>
<body>
  <section class="container my-5">
    <div class="row">
      <div class="col-12">
        <div class="hero">
          <h1>SimpleCms</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="container my-5">
    <div class="row">
      <div class="col-3">
        <button id="toggle-mode" onclick="document .body .setAttribute('data-bs-theme', (document.body.getAttribute('data-bs-theme') && document.body.getAttribute('data-bs-theme') == 'dark' ? 'light' : 'dark')); this.innerHTML = document.body.getAttribute('data-bs-theme')">
          Toggle Light/Dark Mode
        </button>
        <nav class="sidebar-menu">
          <ul>
@foreach($page->content['sections'] ?? [] as $counter => $section)
            <li><a href="#id-{{ $counter }}">{{ $section['title'] }}</a></li>
@endforeach
          </ul>
        </nav>
      </div>
      <div class="col-9">
        <div class="main-content">
@foreach($page->content['sections'] ?? [] as $counter => $section)
          <h2 id="id-{{ $counter }}">{{ $section['title'] }}</h2>
          <?=$section['content'];?>
@endforeach
        </div>
      </div>
    </div>
  </section>

  <div class="container my-5">
    <footer>
       <div class="container">
@if(session('status'))
        <div class="text-center">{{ session('status') }}
@endif
          <form method="POST" action="/contacts">
            @csrf
            <div class="form-group">
              <label for="name">Full name</label>
              <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
              <label for="description">Description:</label>
              <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-6">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-6">
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </div>
            </div>
          </form>
        </div>
    </footer>
  </div>

  <script>
    // Add your JavaScript logic for light-mode/dark-mode toggle and onscroll event here
  </script>
</body>
</html>
