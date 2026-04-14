@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;

    // Helper route active
    $is = fn(...$patterns) => request()->routeIs($patterns);

    // Role integer
    $ROLE_ADMIN = 1;
    $ROLE_AUTHOR = 2;
    $ROLE_USER = 3;

    $user = Auth::user();
    $role = (int) ($user->role ?? $ROLE_USER);

    $isAdmin = $user && $role === $ROLE_ADMIN;
    $isAuthor = $user && $role === $ROLE_AUTHOR;

    $canManageMusic = $isAdmin || $isAuthor;
    $canManageSfx = $isAdmin || $isAuthor;

    $activeDashboard = $is('home');

    $activeMusic = $is(
        'tracks.*',
        'genres.*',
        'tags.*',
        'albums.*'
    );

    $activeSfx = $is(
        'sound_effects.*',
        'sound_categories.*',
        'sound_tags.*',
        'sound_licenses.*',
        'sound_subcategories.*'
    );

    $activePricing = $is('pricing', 'pricing.index');
    $activeSaved = $is('saved.*', 'favorites.*');

    $activeLi = 'active';
@endphp


<div id="sidebar" class="active">

    <div class="sidebar-wrapper active">

        <div class="sidebar-header">

            <div class="d-flex justify-content-between">

                <div class="logo">

                    <a href="{{ Route::has('home') ? route('home') : url('/') }}">
                        BeatHive
                    </a>

                </div>

                <div class="toggler">

                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x bi-middle"></i>
                    </a>

                </div>

            </div>

        </div>


        <div class="sidebar-menu">

            <ul class="menu">


                {{-- DASHBOARD --}}
                <li class="sidebar-item {{ $activeDashboard ? $activeLi : '' }}">

                    <a href="{{ Route::has('home') ? route('home') : url('/') }}" class="sidebar-link">

                        <i class="bi bi-grid-fill"></i>

                        <span>Dashboard</span>

                    </a>

                </li>



                {{-- MUSIC --}}
                @if($canManageMusic)

                    <li class="sidebar-item has-sub {{ $activeMusic ? $activeLi : '' }}">

                        <a href="#" class="sidebar-link">

                            <i class="bi bi-music-note-list"></i>

                            <span>Music</span>

                        </a>

                        <ul class="submenu">


                            <li class="submenu-item">

                                <a href="{{ Route::has('tracks.index') ? route('tracks.index') : '#' }}">

                                    <i class="bi bi-collection me-1"></i>
                                    Explore all music

                                </a>

                            </li>


                            <li class="submenu-item">

                                <a href="{{ Route::has('genres.index') ? route('genres.index') : '#' }}">

                                    <i class="bi bi-tags me-1"></i>
                                    Genres

                                </a>

                            </li>


                            <li class="submenu-item">

                                <a href="{{ Route::has('albums.index') ? route('albums.index') : '#' }}">

                                    <i class="bi bi-emoji-smile me-1"></i>
                                    Moods

                                </a>

                            </li>


                            <li class="submenu-item">

                                <a href="{{ Route::has('albums.index') ? route('albums.index') : '#' }}">

                                    <i class="bi bi-lightbulb me-1"></i>
                                    Themes

                                </a>

                            </li>


                        </ul>

                    </li>

                @else

                    <li class="sidebar-item {{ $activeMusic ? $activeLi : '' }}">

                        <a href="{{ Route::has('tracks.index') ? route('tracks.index') : '#' }}" class="sidebar-link">

                            <i class="bi bi-music-note-list"></i>

                            <span>Music</span>

                        </a>

                    </li>

                @endif



                {{-- SOUND EFFECTS --}}
                @if($canManageSfx)

                    <li class="sidebar-item has-sub {{ $activeSfx ? $activeLi : '' }}">

                        <a href="#" class="sidebar-link">

                            <i class="bi bi-soundwave"></i>

                            <span>Sound Effects</span>

                        </a>

                        <ul class="submenu">


                            <li class="submenu-item">

                                <a href="{{ Route::has('sound_effects.index') ? route('sound_effects.index') : '#' }}">

                                    <i class="bi bi-collection me-1"></i>
                                    Library

                                </a>

                            </li>


                            <li class="submenu-item">

                                <a href="{{ Route::has('sound_effects.create') ? route('sound_effects.create') : '#' }}">

                                    <i class="bi bi-plus-circle me-1"></i>
                                    Add Sound Effect

                                </a>

                            </li>


                            {{-- CATEGORY TYPE --}}
                            <li class="submenu-item">

                                <a href="#">

                                    <i class="bi bi-brush me-1"></i>
                                    Soundscape

                                </a>

                            </li>


                            <li class="submenu-item">

                                <a href="#">

                                    <i class="bi bi-mic me-1"></i>
                                    Foley

                                </a>

                            </li>


                            <li class="submenu-item">

                                <a href="#">

                                    <i class="bi bi-magic me-1"></i>
                                    SoundScoring

                                </a>

                            </li>


                            <li class="submenu-item">

                                <a href="#">

                                    <i class="bi bi-cloud-fog2 me-1"></i>
                                    Ambience

                                </a>

                            </li>



                            @if($isAdmin)


                                <li class="submenu-item">

                                    <a
                                        href="{{ Route::has('sound_categories.index') ? route('sound_categories.index') : '#' }}">

                                        <i class="bi bi-folder-fill me-1"></i>
                                        Categories

                                    </a>

                                </li>


                                <li class="submenu-item">

                                    <a href="{{ Route::has('sound_tags.index') ? route('sound_tags.index') : '#' }}">

                                        <i class="bi bi-tags-fill me-1"></i>
                                        Tags

                                    </a>

                                </li>


                                <li class="submenu-item">

                                    <a href="{{ Route::has('sound_licenses.index') ? route('sound_licenses.index') : '#' }}">

                                        <i class="bi bi-award-fill me-1"></i>
                                        Licenses

                                    </a>

                                </li>


                                <li class="submenu-item">

                                    <a
                                        href="{{ Route::has('sound_subcategories.index') ? route('sound_subcategories.index') : '#' }}">

                                        <i class="bi bi-diagram-3-fill me-1"></i>
                                        Subcategories

                                    </a>

                                </li>

                            @endif


                        </ul>

                    </li>

                @else

                    <li class="sidebar-item {{ $activeSfx ? $activeLi : '' }}">

                        <a href="{{ Route::has('sound_effects.index') ? route('sound_effects.index') : '#' }}"
                            class="sidebar-link">

                            <i class="bi bi-soundwave"></i>

                            <span>Sound Effects</span>

                        </a>

                    </li>

                @endif



                {{-- AUTHORS --}}
                @can('admin-only')

                    <li class="sidebar-item {{ request()->routeIs('author.*') ? $activeLi : '' }}">

                        <a href="{{ route('author.index') }}" class="sidebar-link">

                            <i class="bi bi-people-fill"></i>

                            <span>Authors</span>

                        </a>

                    </li>

                @endcan



                {{-- PRICING --}}
                @if(Route::has('pricing.index'))

                    <li class="sidebar-item {{ $activePricing ? $activeLi : '' }}">

                        <a href="{{ route('pricing.index') }}" class="sidebar-link">

                            <i class="bi bi-currency-dollar"></i>

                            <span>Pricing</span>

                        </a>

                    </li>

                @endif



                {{-- SAVED --}}
                <li class="sidebar-item {{ $activeSaved ? $activeLi : '' }}">

                    <a href="{{ Route::has('saved.index')
    ? route('saved.index')
    : (Route::has('favorites.index') ? route('favorites.index') : '#') }}" class="sidebar-link">

                        <i class="bi bi-bookmark-heart-fill"></i>

                        <span>Saved</span>

                    </a>

                </li>


            </ul>

        </div>

    </div>

</div>