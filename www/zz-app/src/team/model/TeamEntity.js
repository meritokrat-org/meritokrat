angular
    .module('TeamModule')
    .factory('Team', [
        function () {
            return Team;
        }
    ]);

/**
 * Team Entity
 *
 * @constructor
 */
function Team(data) {
    var self = this,
        hashMap = {};

    hashMap.id = null;
    hashMap.title = '';
    hashMap.avatar = '';
    hashMap.location = {};
    hashMap.stat = {};

    self.$hashMap = hashMap;
    self.id = $id;
    self.title = $title;
    self.location = $location;
    self.branch = $branch;
    self.stat = $stat;
    self.avatar = $avatar;
    self.buildings = $buildings;

    init();

    /**
     * Initialization
     */
    function init() {
        if (data === undefined)
            return;
        ['id', 'title', 'location', 'branch', 'stat', 'avatar', 'buildings']
            .forEach(function (prop) {
                if (!data.hasOwnProperty(prop))
                    return;
                self[prop].call(self, data[prop]);
            })
    }

    /**
     * Getter|Setter of Team.Id
     *
     * @param {number=} id
     * @returns {number|Team}
     */
    function $id(id) {
        if (id === undefined) {
            return hashMap.id;
        }

        hashMap.id = parseInt(id);

        return self;
    }

    /**
     * Getter|Setter of Team.Title
     *
     * @param {string=} id
     * @returns {string|Team}
     */
    function $title(title) {
        if (title === undefined) {
            return hashMap.title;
        }

        hashMap.title = title;

        return self;
    }

    /**
     * Getter|Setter of Team.Location
     *
     * @param {Object=} location
     * @returns {Object|Team}
     */
    function $location(location) {
        if (location === undefined) {
            return hashMap.location;
        }

        hashMap.location = location;

        return self;
    }

    /**
     * Getter|Setter of Team.Branch
     *
     * @param {Object=} branch
     * @returns {Object|Team}
     */
    function $branch(branch) {
        if (branch === undefined) {
            return hashMap.branch;
        }

        hashMap.branch = branch;

        return self;
    }

    /**
     * Getter|Setter of Team.Stat
     *
     * @param {Object=} branch
     * @returns {Object|Team}
     */
    function $stat(stat) {
        if (stat === undefined) {
            return hashMap.stat;
        }

        hashMap.stat = stat;

        return self;
    }

    /**
     *
     */
    function $avatar(avatar) {
        if (avatar === undefined) {
            return hashMap.avatar;
        }

        hashMap.avatar = avatar;

        return self;
    }

    /**
     * 
     * @param buildings
     * @returns {*}
     */
    function $buildings(buildings) {
        if (buildings === undefined) {
            return hashMap.buildings;
        }

        hashMap.buildings = buildings;

        return self;
    }
}