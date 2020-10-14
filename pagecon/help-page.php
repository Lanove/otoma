<div class="container-fluid mt-4">
    <style>
        .accordionContent {
            border: 2px solid var(--navbar-color);
            padding: 2px;
            background-color: white;
            z-index: 1;
        }

        .accordionContent.last {
            position: relative;
            top: -6px;
        }

        .accordionContent .accordionButton {
            margin-left: 5px;
        }

        .accordionContent .accordionContent {
            margin-left: 5px;
        }

        .accordionButton {
            overflow-wrap: anywhere;
            background-color: var(--navbar-color);
            color: #fff;
            cursor: pointer;
            min-height: 60px;
            margin-top: 1px;
            padding: 10px;
            z-index: 2;
        }

        .accordion p {
            color: #16252a !important;
        }

        .accordion .first {
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .accordion .last {
            border-radius: 0 0 0.5rem 0.5rem;
        }

        .accordion .caret {
            margin-left: auto !important;
            font-size: 38px;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <h2 style="position:relative;left:-10px;">Asked Questions</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div id="main-accordion" class="accordion">
                <div class="accordionButton first d-flex align-items-center">
                    <h3>
                        Cara mem
                    </h3>
                    <i class="fas fa-caret-down caret"></i>
                </div>
                <div class="accordionContent">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
                        ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                        aliquip ex ea commodo consequat. Duis aute irure dolor in
                        reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                        pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                        culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                    <div id="sub-accordion1">
                        <div class="accordionButton first d-flex">
                            <h3>Item a</h3>
                            <i class="fas fa-caret-down caret"></i>
                        </div>
                        <div class="accordionContent">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                                nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                                sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                        </div>

                        <div class="accordionButton last d-flex">
                            <h3>Item b</h3>
                            <i class="fas fa-caret-down caret"></i>
                        </div>
                        <div class="accordionContent last">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                                nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                                sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                        </div>

                        <div class="accordionButton last d-flex">
                            <h3>Item b</h3>
                            <i class="fas fa-caret-down caret"></i>
                        </div>
                        <div class="accordionContent last">
                            <img alt="Otoma" src="img/logo/otoma_withtext.png" height="42">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                                nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                                sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                        </div>
                    </div>

                </div>

                <div class="accordionButton d-flex align-items-center">
                    <h3>
                        Item w
                    </h3>
                    <i class="fas fa-caret-down caret"></i>
                </div>
                <div class="accordionContent">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
                        ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                        aliquip ex ea commodo consequat. Duis aute irure dolor in
                        reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                        pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                        culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                    <div id="sub-accordion2">
                        <div class="accordionButton first d-flex">
                            <h3>Item a</h3>
                            <i class="fas fa-caret-down caret"></i>
                        </div>

                        <div class="accordionContent">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                                nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                                sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                        </div>

                        <div class="accordionButton last d-flex">
                            <h3>Item b</h3>
                            <i class="fas fa-caret-down caret"></i>
                        </div>

                        <div class="accordionContent last">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                                nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                                sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                        </div>
                    </div>
                </div>


                <div class="accordionButton last d-flex align-items-center">
                    <h3>
                        Item w
                    </h3>
                    <i class="fas fa-caret-down caret"></i>
                </div>
                <div class="accordionContent last">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
                        ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                        aliquip ex ea commodo consequat. Duis aute irure dolor in
                        reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                        pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                        culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                    <div id="sub-accordion3">
                        <div class="accordionButton first d-flex">
                            <h3>Item a</h3>
                            <i class="fas fa-caret-down caret"></i>
                        </div>

                        <div class="accordionContent">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                                nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                                sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                        </div>

                        <div class="accordionButton last d-flex">
                            <h3>Item b</h3>
                            <i class="fas fa-caret-down caret"></i>
                        </div>

                        <div class="accordionContent last">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                                nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                                sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>