<style>
input[type="checkbox"].ios8-switch {
    position: absolute;
    margin: 8px 0 0 16px;
}
input[type="checkbox"].ios8-switch + label {
    position: relative;
    padding: 5px 0 0 50px;
    line-height: 2.0em;
}
input[type="checkbox"].ios8-switch + label:before {
    content: "";
    position: absolute;
    display: block;
    left: 0;
    top: 0;
    width: 40px; /* x*5 */
    height: 24px; /* x*3 */
    border-radius: 16px; /* x*2 */
    background: #fff;
    border: 1px solid #d9d9d9;
    -webkit-transition: all 0.3s;
    transition: all 0.3s;
}
input[type="checkbox"].ios8-switch + label:after {
    content: "";
    position: absolute;
    display: block;
    left: 0px;
    top: 0px;
    width: 24px; /* x*3 */
    height: 24px; /* x*3 */
    border-radius: 16px; /* x*2 */
    background: #fff;
    border: 1px solid #d9d9d9;
    -webkit-transition: all 0.3s;
    transition: all 0.3s;
}
input[type="checkbox"].ios8-switch + label:hover:after {
    box-shadow: 0 0 5px rgba(0,0,0,0.3);
}
input[type="checkbox"].ios8-switch:checked + label:after {
    margin-left: 16px;
}
input[type="checkbox"].ios8-switch:checked + label:before {
    background: #55D069;
}

/* SMALL */

input[type="checkbox"].ios8-switch-sm {
    margin: 5px 0 0 10px;
}
input[type="checkbox"].ios8-switch-sm + label {
    position: relative;
    padding: 0 0 0 32px;
    line-height: 1.3em;
}
input[type="checkbox"].ios8-switch-sm + label:before {
    width: 25px; /* x*5 */
    height: 15px; /* x*3 */
    border-radius: 10px; /* x*2 */
}
input[type="checkbox"].ios8-switch-sm + label:after {
    width: 15px; /* x*3 */
    height: 15px; /* x*3 */
    border-radius: 10px; /* x*2 */
}
input[type="checkbox"].ios8-switch-sm + label:hover:after {
    box-shadow: 0 0 3px rgba(0,0,0,0.3);
}
input[type="checkbox"].ios8-switch-sm:checked + label:after {
    margin-left: 10px; /* x*2 */
}

/* LARGE */

input[type="checkbox"].ios8-switch-lg {
    margin: 10px 0 0 20px;
}
input[type="checkbox"].ios8-switch-lg + label {
    position: relative;
    padding: 7px 0 0 60px;
    line-height: 2.3em;
}
input[type="checkbox"].ios8-switch-lg + label:before {
    width: 50px; /* x*5 */
    height: 30px; /* x*3 */
    border-radius: 20px; /* x*2 */
}
input[type="checkbox"].ios8-switch-lg + label:after {
    width: 30px; /* x*3 */
    height: 30px; /* x*3 */
    border-radius: 20px; /* x*2 */
}
input[type="checkbox"].ios8-switch-lg + label:hover:after {
    box-shadow: 0 0 8px rgba(0,0,0,0.3);
}
input[type="checkbox"].ios8-switch-lg:checked + label:after {
    margin-left: 20px; /* x*2 */
}
</style>
