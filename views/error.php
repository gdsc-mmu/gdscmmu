<?php include "layouts/header.php" ?>
<?php include "partials/navbar.php" ?>

<div class="row">
    <div class="col-6 offset-3">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-header"><%=err.message%></h4>
            <p>
                Aww yeah, you successfully managed to read this super important error
                text message. Good job on your side! Please do better in the future to
                avoid this error message. You should really improve.
            </p>
            <p class="mb-0">
                When you feel like you need to, take a break. Do not go too hard on
                yourself!
            </p>
            <h5 class="my-2">ERROR!!</h5>
            <% if (process.env.NODE_ENV !== "production") { %>
            <p><%=err.stack%></p>
            <% } %>
        </div>
    </div>
</div>

<?php include "layouts/footer.php" ?>
