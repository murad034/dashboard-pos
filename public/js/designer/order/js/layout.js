var dragConfig = {
  addClasses: true,
  helper: draggableHelper,
  appendTo: ".dest",
  containment: ".dest",
  scroll: false,
  zIndex: 11,
};

var droppedConfig = {
  revert: "invalid",
  revertDuration: 500,
  addClasses: true,
  containment: ".dest",
  scroll: false,
  grid: [5, 4],
  zIndex: 11,
  start: function () {
    $(this).css("z-index", 100);
    currentParent = $(this).parent();
  },
  stop: function (event, ui) {
    var draggedPosition = $(ui.helper).position();
    $(ui.helper).css({
      left: Math.round(draggedPosition.left / 5) * 5 + "px",
      top: Math.round(draggedPosition.top / 5) * 5 + "px",
    });
    $(this).draggable({ snap: true });
    $(this).css("z-index", 11);
    $(this).css("border", "solid #fff 3px");
    console.log("stopped");
  },
};

$(".source a").draggable(dragConfig);

$(".dest").droppable({
  drop: function (event, ui) {
    if (!ui.draggable.parent().hasClass("dest")) {
      var clonedHelper = ui.helper.clone().draggable(droppedConfig);

      var draggedPosition = ui.helper.position();
      clonedHelper.css({
        left: Math.round(draggedPosition.left / 5) * 5 + "px",
        top: Math.round(draggedPosition.top / 5) * 5 + "px",
      });
      clonedHelper.addClass("exp").removeClass("ui-draggable-dragging");
      clonedHelper.attr("id", "btn-pos-last");
      clonedHelper.droppable({
        greedy: false,
        tolerance: "touch",
        drop: function (event, ui) {
          ui.draggable.draggable({ snap: true });
        },
      });

      $(this).append(clonedHelper);
    }
  },
});

function draggableHelper(e) {
  return $(
    '<div class="ui-draggable" data-sub="" data-ref=' +
      $(this).attr("data-ref") +
      ">" +
      "<span>" +
      $(e.target).html() +
      "</span>" +
      "</div>"
  );
}
if (window.parent && window.parent.parent) {
  window.parent.parent.postMessage(
    [
      "resultsFrame",
      {
        height: document.body.getBoundingClientRect().height,
        slug: "qNaHE",
      },
    ],
    "*"
  );
}
window.name = "result";

//delete a key
function del() {
  $(".selected").remove();
}

function clearLayout() {
  if (confirm("Are you sure you want to clear everything from the layout?")) {
    $(".exp").remove();
    save_changes();
    console.log("Thing was saved to the database.");
  } else {
    console.log("Thing was not saved to the database.");
  }
}


$(document).on("click", ".layout-item", function () {
  $(".layout-item").each(function (a) {
    $(this).removeClass("menu-selected");
    $(".exp").removeClass("selected");
  });
  $(this).addClass("menu-selected");
});


$(document).on("click", ".sublink-list", function () {
  $(".sublink-list").each(function (a) {
    $(this).removeClass("sublink-selected");
  });
  $(this).addClass("sublink-selected");
});


function checkDisable() {
  if ($(".layout-item").hasClass("menu-selected")) {
  } else {
    $(".can-disbl-btns").addClass("disable-div");
    $(".custom-action-icon").addClass("disable-div");
  }
}
