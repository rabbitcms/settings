/// <reference path="../../../../backend/Assets/backend/dt/index.d.ts" />
define(["require", "exports", "rabbitcms/backend"], function (require, exports, backend_1) {
    "use strict";
    var Settings = (function (_super) {
        __extends(Settings, _super);
        function Settings() {
            return _super.apply(this, arguments) || this;
        }
        Settings.prototype.init = function (portlet) {
        };
        return Settings;
    }(backend_1.MicroEvent));
    return new Settings();
});
//# sourceMappingURL=settings.js.map