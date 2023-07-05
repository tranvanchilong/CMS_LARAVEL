/*
 Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
*/
function getFile(U) {
  var X = new XMLHttpRequest();
  X.open('GET', U, false);
  X.send();
  return X.responseText;
}
var template1 = getFile(CKEDITOR.getUrl(CKEDITOR.plugins.getPath("templates") + "templates/template1.html"));

CKEDITOR.addTemplates("default", {
  imagesPath: CKEDITOR.getUrl(CKEDITOR.plugins.getPath("templates") + "templates/images/"), templates:
    [
      {
        title: "Template 1",
        image: "template1.gif",
        description: "Template 1",
        html: template1
      }
    ]
  }
);