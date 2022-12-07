let id = document.getElementById("drawflow");
let tagList;
let storeList;
let campaignList;
let dataToImport;

function getData() {
    let result;
    $.ajax({
        url: "/automation/builder-api",
        async: false,
        method: 'GET',
        data: {
            id: template_id
        }
    }).done(function (data) {
        if (data.status === "success") {
            result = data.data;
        } else {
            toastr.error('add event failed', 'error', {
                timeOut: 3000
            });
        }
    }).fail(function (jqXHR, status, errorThrown) {
        alert(status + "<br>" + errorThrown);
    });
    return result;
}

let get_data = getData();
if (get_data !== undefined) {
    tagList = get_data.tags;
    storeList = get_data.stores;
    let automation_data = get_data.data;
    if (automation_data[0].automationdata !== undefined) {
        dataToImport = automation_data[0].automationdata;
    } else {
        dataToImport = {
            "drawflow": {
                "Home": {
                    "data": {}
                }
            }
        };
    }
} else {
    tagList = [];
    storeList = [];
    dataToImport = {
        "drawflow": {
            "Home": {
                "data": {}
            }
        }
    };

}


const editor = new Drawflow(id);
editor.reroute = true;

editor.start();
editor.import(dataToImport);


// Events!
editor.on('nodeCreated', function (id) {
    // console.log("Node created " + id);
})

editor.on('nodeRemoved', function (id) {
    // console.log("Node removed " + id);
})

editor.on('nodeSelected', function (id) {
    // console.log("Node selected " + id);
})

editor.on('moduleCreated', function (name) {
    // console.log("Module Created " + name);
})

editor.on('moduleChanged', function (name) {
    // console.log("Module Changed " + name);
})

editor.on('connectionCreated', function (connection) {
    // console.log('Connection created');
    // console.log(connection);
})

editor.on('connectionRemoved', function (connection) {
    // console.log('Connection removed');
    // console.log(connection);
})

editor.on('mouseMove', function (position) {
    // console.log('Position mouse x:' + position.x + ' y:' + position.y);
})

editor.on('nodeMoved', function (id) {
    // console.log("Node moved " + id);
})

editor.on('zoom', function (zoom) {
    // console.log('Zoom level ' + zoom);
})

editor.on('translate', function (position) {
    // console.log('Translate x:' + position.x + ' y:' + position.y);
})

editor.on('addReroute', function (id) {
    // console.log("Reroute added " + id);
})

editor.on('removeReroute', function (id) {
    // console.log("Reroute removed " + id);
})

/* DRAG EVENT */

/* Mouse and Touch Actions */

let elements = document.getElementsByClassName('drag-drawflow');
for (let i = 0; i < elements.length; i++) {
    elements[i].addEventListener('touchend', drop, false);
    elements[i].addEventListener('touchmove', positionMobile, false);
    elements[i].addEventListener('touchstart', drag, false);
}

let mobile_item_selec = '';
let mobile_last_move = null;

function positionMobile(ev) {
    mobile_last_move = ev;
}

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    if (ev.type === "touchstart") {
        mobile_item_selec = ev.target.closest(".drag-drawflow").getAttribute('data-node');
    } else {
        ev.dataTransfer.setData("node", ev.target.getAttribute('data-node'));
    }
}

function drop(ev) {
    if (ev.type === "touchend") {
        let parentdrawflow = document.elementFromPoint(mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY).closest("#drawflow");
        if (parentdrawflow != null) {
            addNodeToDrawFlow(mobile_item_selec, mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY);
        }
        mobile_item_selec = '';
    } else {
        ev.preventDefault();
        let data = ev.dataTransfer.getData("node");
        addNodeToDrawFlow(data, ev.clientX, ev.clientY);
    }

}

function buildTagDropDown() {

    let dropdown = '<select>';
    for (let i = 0; i < tagList.length; i++) {
        let option = "<option value=\"" + tagList[i]["tagid"] + "\">" + tagList[i]["tagname"] + "</option>";
        dropdown = dropdown + option;

    }
    dropdown = dropdown + "</select>";
    return dropdown;
}

function buildStoreDropDown() {

    let dropdown = '<select>';
    for (let i = 0; i < storeList.length; i++) {
        let option = "<option value=\"" + storeList[i]["locationid"] + "\">" + storeList[i]["locationname"] + "</option>";
        dropdown = dropdown + option;

    }
    dropdown = dropdown + "</select>";
    return dropdown;
}

function addNodeToDrawFlow(name, pos_x, pos_y) {
    let tagSelect = buildTagDropDown();
    let storeSelect = buildStoreDropDown();
    if (editor.editor_mode === 'fixed') {
        return false;
    }
    pos_x = pos_x * (editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)) - (editor.precanvas.getBoundingClientRect().x * (editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)));
    pos_y = pos_y * (editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)) - (editor.precanvas.getBoundingClientRect().y * (editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)));


    switch (name) {
        case 'customer':
            let customer = `
                        <div>
                          <div class="title-box"><i class="fas fa-user"></i> Customer </div>
                            <div class="box">
                            </div>
                        </div>
                        `;
            editor.addNode('customer', 0, 1, pos_x, pos_y, 'customer', {}, customer);
            break;
        case 'hastag':

            let hastagchat = `
                              <div>
                                <div class="title-box"><i class="fas fa-tag"></i> Has Tag </div>
                                <div class="box">` + tagSelect + `
                                    <br></br>
                                  <select df-channel>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                  </select>
                                </div>
                              </div>
                            `;
            editor.addNode('hastag', 1, 1, pos_x, pos_y, 'hastag', {}, hastagchat);
            break;
        case 'visitstore':
            let visitstoretemplate = `
                              <div>
                                <div class="title-box"><i class="fas fa-store"></i> Has visited Store </div>
                                <div class="box">` + storeSelect + `
                                    <br></br>
                                  <select df-channel>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                  </select>
                                </div>
                              </div>
                            `;
            editor.addNode('visitstore', 1, 1, pos_x, pos_y, 'visitstore', {"name": ''}, visitstoretemplate);
            break;
        case 'salevalue':
            let salevaluebot = `
                          <div>
                            <div class="title-box"><i class="fas fa-shopping-cart"></i> Sales Value </div>
                            <div class="box">
                              <input type="text" df-name>
                                    <br></br>
                                  <select df-channel>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                  </select>
                            </div>
                          </div>
                          `;
            editor.addNode('salevalue', 1, 1, pos_x, pos_y, 'salevalue', {}, salevaluebot);
            break;
        case 'lastsale':
            let lastsale = `
                          <div>
                            <div class="title-box"><i class="fas fa-shopping-bag"></i> Last Sales </div>
                            <div class="box">
                              <input type="text" df-name>
                                    <br></br>
                                  <select df-channel>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                  </select>
                            </div>
                          </div>
                          `;
            editor.addNode('lastsale', 1, 1, pos_x, pos_y, 'lastsale', {}, lastsale);
            break;
        case 'and':
            let and = `
                          <div>
                            <div class="title-box"><i class="fas fa-check"></i> AND </div>
                          </div>
                          `;
            editor.addNode('and', 1, 1, pos_x, pos_y, 'and', {}, and);
            break;
        case 'or':
            let or = `
                          <div>
                            <div class="title-box"><i class="fab fa-angle-up"></i> OR </div>
                          </div>
                            `;
            editor.addNode('or', 1, 1, pos_x, pos_y, 'or', {}, or);
            break;
        case 'campaign':
            let campaign = `
                              <div>
                                <div class="title-box"><i class="fas fa-campground"></i> Send Campaign </div>
                                <div class="box">` + storeSelect + `
                                </div>
                              </div>
                            `;
            editor.addNode('campaign', 1, 1, pos_x, pos_y, 'campaign', {}, campaign);
            break;

        case 'addtag':
            let addtag = `
                              <div>
                                <div class="title-box"><i class="fas fa-tag"></i> Add Tag </div>
                                <div class="box">` + tagSelect + `
                                </div>
                              </div>
                            `;
            editor.addNode('addtag', 1, 1, pos_x, pos_y, 'template', {}, addtag);
            break;
        default:
    }
}

let transform = '';

function showpopup(e) {
    e.target.closest(".drawflow-node").style.zIndex = "9999";
    e.target.children[0].style.display = "block";
    //document.getElementById("modalfix").style.display = "block";

    //e.target.children[0].style.transform = 'translate('+translate.x+'px, '+translate.y+'px)';
    transform = editor.precanvas.style.transform;
    editor.precanvas.style.transform = '';
    editor.precanvas.style.left = editor.canvas_x + 'px';
    editor.precanvas.style.top = editor.canvas_y + 'px';

    //e.target.children[0].style.top  =  -editor.canvas_y - editor.container.offsetTop +'px';
    //e.target.children[0].style.left  =  -editor.canvas_x  - editor.container.offsetLeft +'px';
    editor.editor_mode = "fixed";

}

function closemodal(e) {
    e.target.closest(".drawflow-node").style.zIndex = "2";
    e.target.parentElement.parentElement.style.display = "none";
    //document.getElementById("modalfix").style.display = "none";
    editor.precanvas.style.transform = transform;
    editor.precanvas.style.left = '0px';
    editor.precanvas.style.top = '0px';
    editor.editor_mode = "edit";
}

function changeModule(event) {
    let all = document.querySelectorAll(".menu ul li");
    for (let i = 0; i < all.length; i++) {
        all[i].classList.remove('selected');
    }
    event.target.classList.add('selected');
}

function changeMode(option) {

    //console.log(lock.id);
    if (option === 'lock') {
        lock.style.display = 'none';
        unlock.style.display = 'block';
    } else {
        lock.style.display = 'block';
        unlock.style.display = 'none';
    }

}

function saveDraw() {

    $.ajax({
        type: "POST",
        url: "/automation/builder-api",
        data: {
            id: template_id,
            data: JSON.stringify(editor.export())
        },
        async: false,
        success: function (data) {
            if (data.status === "success") {
                toastr.success('add event success', 'Success', {
                    timeOut: 3000
                });
                window.location.href = window.location.origin + "/automation";
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


}
