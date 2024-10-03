type SigninPayload = {
  email: string;
  password: string;
};

export interface SignInSuccess {
  token: string;
  user: object;
}

export interface ApiResponse<TData = any> {
  message: string;
  data: TData;
  errors: object;
  status: number;
}

export interface GetTaskResponse {
  tasks: Array;
  task_statuses: Array;
  task_priorities: Array;
}

export interface Task {
  id?: integer;
  label: string;
  name: string;
  priority: string;
  status: string;
  note?: string;
  children?: Array<Task>;
}

export interface GetSingleTaskResponse {
  task: {
    id: integer;
    label: string;
    name: string;
    priority: string;
    status: string;
    note?: string;
    children?: Array<Task>;
  };
}

export interface CreateTaskPayload {
  parent_id?: integer;
  name: string;
  label: string;
  priority: string;
  note: string;
  status: string;
}

export interface UpdateTaskPayload extends CreateTaskPayload {
  id: integer | string;
}

export interface TaskRowData {
  id?: integer;
  name: string;
  label: string;
  priority: string;
  status: string;
}
