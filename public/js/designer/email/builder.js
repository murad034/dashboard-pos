// Utility methods
function request(method, url, data, callback) {
    let req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState === 4 && req.status === 200) {
            callback(req.responseText);
        } else if (req.readyState === 4 && req.status !== 200) {
            console.error('Can not complete request. Please check you entered a valid PLUGIN_ID and SECRET_KEY values');
        }
    };
    req.open(method, url, true);
    if (method !== 'GET') {
        req.setRequestHeader('content-type', 'application/json');
    }
    req.send(data);
}

function loadDemoTemplate(callback) {
    request('GET', 'https://raw.githubusercontent.com/ardas/stripo-plugin/master/Public-Templates/Custom-Templates/Finance/Christmas/Christmas.html', null, function (html) {
        request('GET', 'https://raw.githubusercontent.com/ardas/stripo-plugin/master/Public-Templates/Custom-Templates/Finance/Christmas/Christmas.css', null, function (css) {

            $.ajax({
                url: "/email-marketing-designer/editor/edit-api/" + template_id,
                method: 'GET',
                async: false,
            }).done(function (data) {
                if (data.status === "success") {
                    let template_data;
                    if (data.data.length !== 0) {
                        template_data = data.data[0]["templatedata"];
                    }
                    if (template_data !== undefined) {
                        callback({
                            html: template_data,
                            css: css
                        });
                    } else {
                        callback({
                            html: html,
                            css: css
                        });
                    }
                } else {
                    callback({
                        html: html,
                        css: css
                    });
                }
            }).fail(function (jqXHR, status, errorThrown) {
                callback({
                    html: html,
                    css: css
                });
            });


        });
    });
}

// Call this function to start plugin.
// For security reasons it is STRONGLY recommended NOT to store your PLUGIN_ID and SECRET_KEY on client side.
// Please use backend middleware to authenticate plugin.
// See documentation: https://stripo.email/plugin-api/
function initPlugin(template) {
    const apiRequestData = {
        emailId: 1234
    };
    const script = document.createElement('script');
    script.id = 'stripoScript';
    script.type = 'text/javascript';
    script.src = 'https://plugins.stripo.email/static/latest/stripo.js';
    script.onload = function () {
        window.Stripo.init({
            settingsId: 'stripoSettingsContainer',
            previewId: 'stripoPreviewContainer',
            codeEditorButtonId: 'codeEditor',
            undoButtonId: 'undoButton',
            redoButtonId: 'redoButton',
            locale: 'en',
            html: template.html,
            css: template.css,
            notifications: {
                info: notifications.info.bind(notifications),
                error: notifications.error.bind(notifications),
                warn: notifications.warn.bind(notifications),
                loader: notifications.loader.bind(notifications),
                hide: notifications.hide.bind(notifications),
                success: notifications.success.bind(notifications)
            },
            mergeTags: [{
                category: 'MailChimp',
                entries: [{
                    label: "mergeTag.label.firstName",
                    "value": "*|FNAME|*"
                }]
            }],
            apiRequestData: apiRequestData,
            userFullName: 'Plugin Demo User',
            versionHistory: {
                changeHistoryLinkId: 'changeHistoryLink',
                onInitialized: function (lastChangeIndoText) {
                    $('#changeHistoryContainer').show();
                }
            },
            getAuthToken: function (callback) {
                request('POST', 'https://plugins.stripo.email/api/v1/auth',
                    JSON.stringify({
                        pluginId: '82b817a050e84800967321a88c5fc991',
                        secretKey: 'b4a9ade7aa1c41908a23735601f9656e'
                    }),
                    function (data) {
                        callback(JSON.parse(data).token);
                    });
            }
        });
    };
    document.body.appendChild(script);
}

loadDemoTemplate(initPlugin);

function save() {
    // window.StripoApi.compileEmail(function(error, html, ampHtml, ampErrors) {


    // }, true);

    window.StripoApi.getTemplate(function (html) {
        $.ajax({
            type: "POST",
            url: "/email-marketing-designer/editor/edit-api",
            data: {
                _token: csrfToken,
                data: {
                    id: template_id,
                    data: {
                        templatedata: html
                    }
                }
            },
            async: false,
            success: function (data) {
                if (data.status === "success") {
                    toastr.success('add event success', 'Success', {
                        timeOut: 3000
                    });
                    window.location.href = window.location.origin + "/email-marketing-designer";
                } else {
                    toastr.error('add event failed', 'error', {
                        timeOut: 3000
                    });
                }

            },
            error: function () {
                toastr.error('add event failed', 'error', {
                    timeOut: 3000
                })
            }
        });
    })


}
