const adminManagement = [

  {
    section: '',

    items: [

      { name: 'Dashboard', path: '/dashboard' }
    ]
  },

  {
    section: 'Management',

    items: [

      { name: 'Users', path: '/users' },

      { name: 'Meters', path: '/meters' },

      { name: 'Buildings', path: '/buildings' }
    ]
  },

  {
    section: 'Insights',

    items: [

      { name: 'Analytics', path: '/analytics' },

      { name: 'Export Reports', path: '/reports' }
    ]
  }
]

const operationsMenu = [

  {
    section: 'Operations',

    items: [

      { name: 'Approvals', path: '/approvals' },

      { name: 'Anomalies', path: '/anomalies' }
    ]
  }
]

export const menu = {

  super_admin: [

    ...adminManagement,

    {
      section: 'Calendar',

      items: [

        { name: 'Facilities', path: '/facilities' },

        { name: 'Reservations', path: '/schedules' }
      ]
    }
  ],

  campus_admin: [

    ...adminManagement,

    ...operationsMenu,

    {
      section: 'Calendar',

      items: [

        { name: 'Facilities', path: '/facilities' },

        { name: 'Reservations', path: '/schedules' }
      ]
    }
  ],

  staff: [

    ...adminManagement,

    ...operationsMenu
  ],

  calendar_admin: [

    {
      section: '',

      items: [

        { name: 'Dashboard', path: '/dashboard' }
      ]
    },

    {
      section: 'Calendar Management',

      items: [

        { name: 'Facilities', path: '/facilities' },

        { name: 'Reservations', path: '/schedules' }
      ]
    }
  ]
}