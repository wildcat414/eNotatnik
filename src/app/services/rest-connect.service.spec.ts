import { TestBed } from '@angular/core/testing';

import { RestConnectService } from './rest-connect.service';

describe('RestConnectService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: RestConnectService = TestBed.get(RestConnectService);
    expect(service).toBeTruthy();
  });
});
