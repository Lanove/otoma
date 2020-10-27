    <style>
        #dashboard #content {
            padding: 10px;
        }

        .contact {
            padding: 4%;
        }

        .col-md-3 {
            background: var(--navbar-color);
            padding: 4%;
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .contact-info h2 {
            margin-bottom: 10%;
            color: white;
        }

        .contact-info h5 {
            color: white;
        }

        .col-md-9 {
            background: #fff;
            padding: 3%;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .contact-form label {
            font-weight: 600;
        }

        .contact-form button {
            background: #25274d;
            color: #fff;
            font-weight: 600;
            width: 25%;
        }

        .contact-form button:focus {
            box-shadow: none;
        }

        @media (max-width: 767.98px) {
            .col-md-3 {
                background: var(--navbar-color);
                padding: 3%;
                border-radius: 0.5rem 0.5rem 0 0;
            }

            .col-md-9 {
                border-radius: 0 0 0.5rem 0.5rem;
            }

            .contact-info h2 {
                margin-bottom: 3%;
            }
        }
    </style>

    <div class="container contact mb-5">
        <script>
            function copyToClipboard(elem) {
                // create hidden text element, if it doesn't already exist
                var targetId = "_hiddenCopyText_";
                var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
                var origSelectionStart, origSelectionEnd;
                if (isInput) {
                    // can just use the original source element for the selection and copy
                    target = elem;
                    origSelectionStart = elem.selectionStart;
                    origSelectionEnd = elem.selectionEnd;
                } else {
                    // must use a temporary form element for the selection and copy
                    target = document.getElementById(targetId);
                    if (!target) {
                        var target = document.createElement("textarea");
                        target.style.position = "absolute";
                        target.style.left = "-9999px";
                        target.style.top = "0";
                        target.id = targetId;
                        document.body.appendChild(target);
                    }
                    target.textContent = elem.textContent;
                }
                // select the content
                var currentFocus = document.activeElement;
                target.focus();
                target.setSelectionRange(0, target.value.length);

                // copy the selection
                var succeed;
                try {
                    succeed = document.execCommand("copy");
                } catch (e) {
                    succeed = false;
                }
                // restore original focus
                if (currentFocus && typeof currentFocus.focus === "function") {
                    currentFocus.focus();
                }

                if (isInput) {
                    // restore prior selection
                    elem.setSelectionRange(origSelectionStart, origSelectionEnd);
                } else {
                    // clear temporary content
                    target.textContent = "";
                }
            }
        </script>
        <div class="row" style="-webkit-box-shadow: 0px 0px 53px 4px rgba(0,0,0,0.36);
-moz-box-shadow: 0px 0px 53px 4px rgba(0,0,0,0.36);
box-shadow: 0px 0px 53px 4px rgba(0,0,0,0.36);border-radius:0.5rem;">
            <div class="col-md-3">
                <div class="contact-info" style="
    text-align: center;
">
                    <i class="fas fa-envelope" style="color: white;font-size: 80px;"></i>
                    <h2>Kontak Kami</h2>
                    <h5>Silahkan isi form untuk menghubungi kami. Kami akan menjawab sesegera mungkin.</h5>
                </div>
            </div>
            <div class="col-md-9">
                <div class="contact-form">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="name">Nama:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nameContact" placeholder="Masukkan nama anda" name="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="phone">Nomor HP:</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="phoneContact" placeholder="Masukkan nomor HP anda" name="phone">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Email:</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="emailContact" placeholder="Masukkan email anda" name="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="message">Pesan:</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="5" id="messageContact"></textarea>
                        </div>
                    </div>
                    <span class="ml-3" style="display:block;" id="notifContact"></span>
                    <button type="submit" class="btn btn-primary ml-3" id="submitContact">Kirim</button>

                    <span class="text-muted ml-3 mt-2" style="display:block;">
                        Atau hubungi kami di
                        <a href="#0" id="xeR" data-toggle="popover" data-trigger="focus" data-placement="left" data-content="Tersalin ke clipboard!" onclick="copyToClipboard(this);">085843503354</a>
                        atau
                        <a href="#0" id="xEr" data-toggle="popover" data-trigger="focus" data-placement="left" data-content="Tersalin ke clipboard!" onclick="copyToClipboard(this);">arzaki@otoma.my.id</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    </div>