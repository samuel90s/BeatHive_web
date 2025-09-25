<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield("title","BeatHive – Stock Music")</title>
    @include("includes.head-scripts")

<body>
    <div id="app">
        <div id="main" class="layout-horizontal">
            <!-- Header -->
            @include("includes.header")
            <!-- Content -->
            @yield("content")

            <!-- Footer -->
            @include("includes.footer")
            <!-- end footer -->
        </div>
    </div>
    <!-- scripts -->
     @include("includes.scripts")
    <!-- end scripts -->

</body>

</html>