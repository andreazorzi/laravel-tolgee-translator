@if (!empty(config("tolgee.project_id")) && (config("app.env") == 'local' || !config("tolgee.sync_on_production")))
    <div id="tolgee" class="position-fixed bottom-0 end-0 p-3" title="Sync Translations">
        <span hx-post="{{route("tolgee.sync")}}" hx-target="#request-response" role="button">
            <img src="https://docs.tolgee.io/img/tolgeeLogo.svg" class="bg-body-secondary p-2 rounded" style="width: 60px;">
        </span>
    </div>
@endif