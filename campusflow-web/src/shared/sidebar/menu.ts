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

        { name: 'Events', path: '/events' }
      ]
    }
  ],

  campus_admin: [

    ...adminManagement,

    ...operationsMenu,

    {
      section: 'Calendar',

      items: [

        { name: 'Events', path: '/events' }
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
      section: 'Calendar',

      items: [

        { name: 'Events', path: '/events' },

        { name: 'Calendar', path: '/calendar' }
      ]
    }
  ]
}